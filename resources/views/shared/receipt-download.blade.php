{{-- Rasterises the visible receipt to a PNG and saves it to the user's device.
     Loaded by both the receipt modal and the standalone receipt page. --}}

<script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.8/dist/html2canvas-pro.min.js"></script>
<script>
    function receiptImageFileName(btn) {
        if (btn && btn.dataset && btn.dataset.file) {
            return btn.dataset.file;
        }
        return 'receipt.png';
    }

    async function downloadReceiptImage(btn) {
        const scope = document.getElementById('receiptModalContent') || document.body;
        const target = scope.querySelector('.receipt-wrapper')
            || document.querySelector('.receipt-wrapper');

        if (!target) {
            alert('Receipt not found to save.');
            return;
        }

        if (typeof html2canvas === 'undefined') {
            alert('Image library failed to load. Check your internet connection.');
            return;
        }

        const originalHtml = btn ? btn.innerHTML : null;

        if (btn) {
            btn.setAttribute('aria-busy', 'true');
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving';
        }

        try {
            const canvas = await html2canvas(target, {
                scale: 2,
                backgroundColor: '#ffffff',
                useCORS: true,
                logging: false,
                // keep the action buttons out of the saved picture
                ignoreElements: function (el) {
                    return el.classList && el.classList.contains('no-print');
                }
            });

            const link = document.createElement('a');
            link.download = receiptImageFileName(btn);
            link.href = canvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (error) {
            alert('Could not save the receipt image. Please try again.');
            console.error(error);
        } finally {
            if (btn) {
                btn.removeAttribute('aria-busy');
                btn.style.pointerEvents = '';
                btn.innerHTML = originalHtml;
            }
        }
    }
</script>
