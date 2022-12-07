<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div
        x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }"
        class="flex"
    >
        <input type="hidden" x-model="state" id="gjs-value" />
        <div class="w-full relative" id="gjs-wrapper">
            <div class="panel__top">
                <div class="panel__basic-actions"></div>
                <div class="panel__devices"></div>
                <div class="panel__switcher"></div>
            </div>
            <div class="editor-row">
                <div class="editor-canvas">
                    <div id="gjs"></div>
                </div>
                <div class="panel__right">
                    <div class="layers-container"></div>
                    <div class="styles-container"></div>
                    <div class="traits-container"></div>
                    <div class="blocks-container"></div>
                </div>
            </div>
        </div>
    </div>
</x-forms::field-wrapper>
