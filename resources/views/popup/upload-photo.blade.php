<section class="p-2">
    <form action="{{ route('upload-firm-photo', [$firm->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file" class="small text-muted">до 10 МБ (jpg, gif, png)</label>
            <input type="file" name="files[]" class="form-control-file" id="file" multiple>
        </div>
        <button type="submit" class="btn btn-success mb-2 btn-sm">Отправить</button>

    </form>
    <script>
        var title = 'Загрузить фото';
        var subtitle = '{{ $firm->name }}';
    </script>
</section>