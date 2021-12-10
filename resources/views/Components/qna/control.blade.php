@props(['id', 'modalTarget'])

<div class="control">
    <i class="bi bi-pencil-square update" data-bs-toggle="modal" data-bs-target="#{{$modalTarget}}"></i>
    <form action="{{ route('post.delete', ['post' => $id]) }}" method="post">
        @method('delete')
        @csrf
        <button>
            <i class="bi bi-trash delete"></i>
        </button>
    </form>
</div>
