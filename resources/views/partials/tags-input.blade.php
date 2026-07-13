<div>
    <label class="block text-sm font-medium text-gray-700">Tags</label>
    <input type="text" name="tags"
        value="{{ old('tags', isset($model) && $model->tags->isNotEmpty() ? $model->tags->pluck('name')->implode(', ') : '') }}"
        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="separadas por vírgula">
    <p class="mt-1 text-xs text-gray-400">Ex.: urgente, vip, reforma</p>
</div>
