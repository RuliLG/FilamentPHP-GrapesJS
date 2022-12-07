const initEditor = () => {
    const $input = document.getElementById('gjs-value');
    const $gjs = document.getElementById('gjs');
    $gjs.innerHTML = $input._x_model.get();

    const clean = [
        '.panel__basic-actions',
        '.panel__devices',
        '.panel__switcher',
        '.layers-container',
        '.blocks-container',
        '.styles-container',
        '.traits-container',
    ];
    for (const el of clean) {
        const $el = document.querySelector(el);
        $el.innerHTML = '';
    }

    const editor = grapesjs.init({
        // Indicate where to init the editor. You can also pass an HTMLElement
        container: '#gjs',
        // Get the content for the canvas directly from the element
        // As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
        fromElement: true,
        // Size of the editor
        height: '100%',
        width: 'auto',
        // Disable the storage manager for the moment
        storageManager: false,
        deviceManager: {
            devices: [{
                name: 'Desktop',
                width: '', // default size
            }, {
                name: 'Mobile',
                width: '320px', // this value will be used on canvas width
                widthMedia: '480px', // this value will be used in CSS @media
            }]
        },
        assetManager: {
            assets: @json($assets),
            upload: '{{ route('grapesjs-editor.files') }}',
        },
        panels: { defaults: [
            {
                id: 'layers',
                el: '.panel__right',
                // Make the panel resizable
                resizable: {
                    maxDim: 350,
                    minDim: 200,
                    tc: 0, // Top handler
                    cl: 1, // Left handler
                    cr: 0, // Right handler
                    bc: 0, // Bottom handler
                    // Being a flex child we need to change `flex-basis` property
                    // instead of the `width` (default)
                    keyWidth: 'flex-basis',
                },
            },
            {
                id: 'panel-switcher',
                el: '.panel__switcher',
                buttons: [{
                    id: 'show-layers',
                    active: true,
                    label: 'Layers',
                    command: 'show-layers',
                    // Once activated disable the possibility to turn it off
                    togglable: false,
                }, {
                    id: 'show-style',
                    active: true,
                    label: 'Styles',
                    command: 'show-styles',
                    togglable: false,
                }, {
                    id: 'show-traits',
                    active: true,
                    label: 'Traits',
                    command: 'show-traits',
                    togglable: false,
                }, {
                    id: 'show-blocks',
                    active: true,
                    label: 'Blocks',
                    command: 'show-blocks',
                    togglable: false,
                },
            ],
        },
        {
            id: 'panel-devices',
            el: '.panel__devices',
            buttons: [{
                id: 'device-desktop',
                label: 'D',
                command: 'set-device-desktop',
                active: true,
                togglable: false,
            }, {
                id: 'device-mobile',
                label: 'M',
                command: 'set-device-mobile',
                togglable: false,
            }],
        }
    ] },
    styleManager: {
        appendTo: '.styles-container',
        sectors: [{
            name: 'Dimension',
            open: false,
            // Use built-in properties
            buildProps: ['width', 'min-height', 'padding'],
            // Use `properties` to define/override single property
            properties: [
                {
                    // Type of the input,
                    // options: integer | radio | select | color | slider | file | composite | stack
                    type: 'integer',
                    name: 'The width', // Label for the property
                    property: 'width', // CSS property (if buildProps contains it will be extended)
                    units: ['px', '%'], // Units, available only for 'integer' types
                    defaults: 'auto', // Default value
                    min: 0, // Min value, available only for 'integer' types
                }
            ]
        },{
            name: 'Extra',
            open: false,
            buildProps: ['background-color', 'box-shadow', 'custom-prop'],
            properties: [
                {
                    id: 'custom-prop',
                    name: 'Custom Label',
                    property: 'font-size',
                    type: 'select',
                    defaults: '32px',
                    // List of options, available only for 'select' and 'radio'  types
                    options: [
                        { value: '12px', name: 'Tiny' },
                        { value: '18px', name: 'Medium' },
                        { value: '32px', name: 'Big' },
                    ],
                }
            ]
        }],
    },
    layerManager: {
        appendTo: '.layers-container'
    },
    traitManager: {
        appendTo: '.traits-container',
    },
    canvas: {
        scripts: @json(array_map('asset', config('pagebuilder.assets.js'))),
        styles: @json(array_map('asset', config('pagebuilder.assets.css'))),
    },
    blockManager: {
        appendTo: '.blocks-container',
        blocks: @json($blocks),
    }
});

// Define commands
editor.Commands.add('show-layers', {
    getRowEl(editor) { return editor.getContainer().closest('.editor-row'); },
    getLayersEl(row) { return row.querySelector('.layers-container') },

    run(editor, sender) {
        const lmEl = this.getLayersEl(this.getRowEl(editor));
        lmEl.style.display = '';
    },
    stop(editor, sender) {
        const lmEl = this.getLayersEl(this.getRowEl(editor));
        lmEl.style.display = 'none';
    },
});
editor.Commands.add('show-blocks', {
    getRowEl(editor) { return editor.getContainer().closest('.editor-row'); },
    getBlocksEl(row) { return row.querySelector('.blocks-container') },

    run(editor, sender) {
        const bmEl = this.getBlocksEl(this.getRowEl(editor));
        bmEl.style.display = '';
    },
    stop(editor, sender) {
        const lmEl = this.getBlocksEl(this.getRowEl(editor));
        lmEl.style.display = 'none';
    },
});
editor.Commands.add('show-styles', {
    getRowEl(editor) { return editor.getContainer().closest('.editor-row'); },
    getStyleEl(row) { return row.querySelector('.styles-container') },

    run(editor, sender) {
        const smEl = this.getStyleEl(this.getRowEl(editor));
        smEl.style.display = '';
    },
    stop(editor, sender) {
        const smEl = this.getStyleEl(this.getRowEl(editor));
        smEl.style.display = 'none';
    },
});
editor.Commands.add('show-traits', {
    getTraitsEl(editor) {
        const row = editor.getContainer().closest('.editor-row');
        return row.querySelector('.traits-container');
    },
    run(editor, sender) {
        this.getTraitsEl(editor).style.display = '';
    },
    stop(editor, sender) {
        this.getTraitsEl(editor).style.display = 'none';
    },
});
editor.Commands.add('set-device-desktop', {
    run: editor => editor.setDevice('Desktop')
});
editor.Commands.add('set-device-mobile', {
    run: editor => editor.setDevice('Mobile')
});

editor.Panels.addPanel({
    id: 'basic-actions',
    el: '.panel__basic-actions',
    buttons: [
        {
            id: 'visibility',
            active: true, // active by default
            className: 'btn-toggle-borders',
            label: '<u>B</u>',
            command: 'sw-visibility', // Built-in command
        }, {
            id: 'export',
            className: 'btn-open-export',
            label: 'Exp',
            command: 'export-template',
            context: 'export-template', // For grouping context of buttons from the same panel
        },
    ]
});

editor.on('update', function () {
    const $input = document.getElementById('gjs-value');
    $input._x_model.set(editor.getHtml());
});
};

document.addEventListener("DOMContentLoaded", () => {
    {{--
        Livewire.hook('component.initialized', (component) => {})
        Livewire.hook('element.initialized', (el, component) => {})
        Livewire.hook('element.updating', (fromEl, toEl, component) => {})
        Livewire.hook('element.updated', (el, component) => {})
        Livewire.hook('element.removed', (el, component) => {})
        Livewire.hook('message.sent', (message, component) => {})
        Livewire.hook('message.failed', (message, component) => {})
        Livewire.hook('message.received', (message, component) => {})
    --}}
    Livewire.hook('message.processed', (message, component) => {
        initEditor();
    });

    initEditor();
});
