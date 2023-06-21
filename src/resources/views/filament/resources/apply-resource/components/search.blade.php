<div>
    <form method="POST" action="{{ route('filament.resources.applies.index') }}">
        <input type="text" name="name" placeholder="名前" class="form-input">
        <input type="text" name="email" placeholder="メールアドレス" class="form-input">
        <input type="date" name="interpreter_number_obtained_on" class="form-input">
        <button type="submit" class="btn btn-primary">{{ __('検索') }}</button>
    </form>
</div>
