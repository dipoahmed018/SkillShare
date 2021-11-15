@props(['id', 'name', 'options', 'max_height' => '15em', 'label' => 'Select Options'])

<div class="select-checkbox closed" id="{{ $id }}" style="max-height: {{ $max_height }}">
    <div class="control">
        <h6>{{ $label }}</h6>
        <i class="bi bi-caret-down"></i>
    </div>
    <div class="options">
        @foreach ($options as $option)
            <label class="option">
                <span class="option-name">
                    {{ $option->name }}
                </span>
                <div class="checkbox">
                    <input type="checkbox" name="{{ $name . '[]' }}" value="{{ $option->id }}"
                        id="{{ $id . '-input-' . $option->id }}" {{ $option->checked == true ? 'checked' : '' }}>
                    <div class="checkmark"></div>
                </div>
            </label>
        @endforeach
    </div>
</div>

@push('checkbox')
    <script>
        const checkbox_id = @json($id);
        const caret_up_icon = 'bi-caret-up'
        const caret_down_icon = 'bi-caret-down'

        const select_checkbox = document.getElementById(checkbox_id)
        const controller = select_checkbox.querySelector('.control')
        const controller_icon = select_checkbox.querySelector('i')
        const options = select_checkbox.querySelector('.options')

        controller.addEventListener("click", () => {
            //changing icon based on options active or inactive
            if (select_checkbox.classList.contains('opened')) {
                select_checkbox.classList.replace('opened', 'closed')
                controller_icon.className = caret_down_icon
                options.style.display = 'none'
            } else {
                select_checkbox.classList.replace('closed', 'opened')
                controller_icon.className = caret_up_icon
                options.style.display = 'flex'
            }
        })
    </script>
@endpush
