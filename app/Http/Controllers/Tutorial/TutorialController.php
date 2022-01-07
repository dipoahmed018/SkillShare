<?php

namespace App\Http\Controllers\Tutorial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\AddVideo;
use App\Http\Requests\Course\DeleteVideo;
use App\Http\Requests\Course\UpdateDetails;
use App\Models\Course;
use App\Models\FileLink;
use App\Models\TutorialDetails;
use App\Services\VideoStream;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TutorialController extends Controller
{
    public function addTutorial(AddVideo $request, Course $course)
    {

        $course_tutorials = $course->tutorialDetails;
        $data = $request->chunk_file ? blobConvert($request->chunk_file) : null;
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->tutorial_name) . $course->id;
        $title = 'please provide your tutorial title';
        $directory = '/' . $directory_name;

        if ($request->header('x-cancel')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ?  $chunk->message : 'something went wrong';
        }

        if ($request->header('x-resumeable')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ? $chunk->file_name : abort($chunk->status, $chunk->message);
        }

        if ($request->header('x-last') == true) {
            //chunk upload
            $chunk = chunkUpload($directory, $data, 'private/course/tutorial/');
            if ($chunk->status == 422) {
                return abort(422, $chunk->message);
            }

            $file = FileLink::create([
                'file_name' => $chunk->file_name,
                'file_link' => 'private/course/tutorial/' . $chunk->file_name,
                'file_type' => 'tutorial',
                'security' => 'private',
                'fileable_type' => 'course',
                'fileable_id' => $course->id,
            ]);

            $tutorial_details = TutorialDetails::create([
                'tutorial_id' => $file->id,
                'title' => $title,
                'order' => $course_tutorials->count() + 1,
            ]);

            $tutorial_details->file_details = $file;
            
            return response()->json(['data' => $tutorial_details, 'error' => false, 'success' => true]);
        }

        $chunk = chunkUpload($directory, $data);

        return $chunk->status == 200 ? response($chunk->file_name, 200) : abort(422, $chunk->message);
    }


    public function updateTitle(Request $request, TutorialDetails $tutorial)
    {
        $request->validate(['title' => 'required|string|min:10|max:150'], $request->all());
        $tutorial->update([
            'title' => $request->title,
        ]);
        if ($request->acceptsJson()) {
            return response()->json($tutorial, 200);
        }
        return redirect()->back();
    }


    public function updateTutorialOrder(Request $request, TutorialDetails $tutorial)
    {
        try {
            $request->validate(['order' => 'required|integer|min:1']);
            $course = $tutorial->course;
            if ($request->user()->cannot('update', $course)) {
                throw new AuthorizationException("you don't have to change order of this course");
            }
            if ($request->order < $tutorial->order) {
                $course->tutorialDetails()->whereBetween('order', [$request->order, $tutorial->order - 1])->increment('order', 1);
                
                $tutorial->update(['order' => $request->order]);
            }
            if ($request->order > $tutorial->order) {
                $course->tutorialDetails()->whereBetween('order', [$request->order, $tutorial->order + 1])->decrement('order', 1);
                $tutorial->update(['order' => $request->order]);
            }
            if ($request->acceptsJson()) {
                return response()->json($tutorial);
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteTutorial(Request $request, TutorialDetails $tutorial, Course $course)
    {
        try {
            if ($request->user()->id !== $course->owner) {
                throw new AuthorizationException('You are not the owner of this course');
            }
            Log::channel('event')->info('i was here');
            $file = $tutorial->tutorial_video;
            $course->tutorialDetails()->where('tutorial_details.order', '>', $tutorial->order)->decrement('order', 1);
            // TutorialDetails::query()->where('order','>', $tutorial->order)->decrement('order', 1);
            Storage::delete($file->file_link);
            $file->delete();
            $tutorial->delete();
            if ($request->acceptsJson()) {
                return response()->json(['data' => $tutorial, 'error' => false, 'success' => true], 200);
            }
            return redirect('/show/course/' . $course->id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function streamTutorial(Request $request, TutorialDetails $tutorial, Course $course)
    {
        if ($request->user()->cannot('tutorial', $course) && $request->user()->cannot('update', $course)) {
            return abort(401, 'you are not autorized to access this course tutorial');
        }
        $file_details = FileLink::findOrFail($tutorial->tutorial_id);
        $stream = new VideoStream(storage_path('/app//' . $file_details->file_link));
        $stream->start();
    }
}
