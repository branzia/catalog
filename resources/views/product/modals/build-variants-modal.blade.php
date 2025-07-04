<div x-data="{ selected: {} }">
    <template x-for="(attribute, index) in @js($attributes)">
        <div class="mb-4">
            <h3 class="font-semibold text-lg" x-text="attribute.name"></h3>
            <div class="flex flex-wrap gap-2 mt-2">
                <template x-for="value in attribute.values">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" 
                               :name="'selected_attributes[' + attribute.id + '][]'" 
                               :value="value.id"
                               class="border-gray-300 rounded" />
                        <span x-text="value.value"></span>
                    </label>
                </template>
            </div>
        </div>
    </template>
</div>