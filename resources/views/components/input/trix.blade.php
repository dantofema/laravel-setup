@props([
    'disabled' => false,
    'initialValue' => ''
    ])

<div
        wire:ignore
        x-data
        @trix-blur="$dispatch('change', $event.target.value)"
        @trix-attachment-add="trixAttachmentUpload($event.attachment)"
        {{ $attributes }}
>

    <input
            id="x"
            type="hidden"
            value="{{ $initialValue }}"
    >

    <trix-editor
            input="x"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            {{ $disabled ? 'disabled' : '' }}
    ></trix-editor>

    @push('js')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"
                integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css"
              integrity="sha512-CWdvnJD7uGtuypLLe5rLU3eUAkbzBR3Bm1SFPEaRfvXXI2v2H5Y0057EMTzNuGGRIznt8+128QIDQ8RqmHbAdg=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>

        <script type="text/javascript">
            function trixAttachmentUpload(attachment) {

            @this.upload('newTrixAttachmentFile', attachment.file,
                function (uploadedUrl) {
                    const eventName = `myapp:trix-upload-completed:$(btoa(uploadedUrl))`

                    const listener = function (event) {
                        attachment.setAttributes(event.detail)
                        window.removeEventListener(eventName, listener)
                    }

                    window.addEventListener(eventName, listener)
                @this.call('trixAttachmentUpload', uploadedUrl, eventName)
                },
                function () {
                },
                function (event) {
                    attachment.setUploadProgress(event.detail.progress)
                }
            );

            }


        </script>
    @endpush

</div>
