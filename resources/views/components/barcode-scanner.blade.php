<template>
    <div>
        <div id="interactive" class="viewport"></div>
        <div>{{ barcodeData }}</div>
    </div>
</template>

<script>
    import Quagga from 'quagga';

    export default {
        mounted() {
            Quagga.init(
                {
                    inputStream: {
                        name: 'Live',
                        type: 'LiveStream',
                        target: document.querySelector('#interactive'),
                        constraints: {
                            width: 480,
                            height: 320,
                            facingMode: 'environment', // hoặc 'user' nếu muốn sử dụng camera trước
                        },
                    },
                    decoder: {
                        readers: ['ean_reader'], // Có thể thay đổi thành loại mã barcode bạn muốn quét
                    },
                },
                function (err) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    console.log('Initialization finished. Ready to start');
                    Quagga.start();
                }
            );

            Quagga.onDetected(this.onBarcodeDetected);
        },
        beforeUnmount() {
            Quagga.offDetected(this.onBarcodeDetected);
            Quagga.stop();
        },
        methods: {
            onBarcodeDetected(result) {
                this.barcodeData = result.codeResult.code;
            },
        },
        data() {
            return {
                barcodeData: '',
            };
        },
    };
</script>

<style scoped>
    .viewport {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .viewport > canvas,
    .viewport > video {
        width: 100%;
        height: 100%;
    }

    .viewport canvas:first-child,
    .viewport video:first-child {
        position: absolute;
        left: 0;
        top: 0;
        z-index: 10;
    }
</style>
