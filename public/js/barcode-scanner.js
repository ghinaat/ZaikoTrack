

let selectedDeviceId = null;
const codeReader = new ZXing.BrowserMultiFormatReader();
const sourceSelect = $("#pilihKamera");

// Event listener for camera selection change
$(document).on('change', '#pilihKamera', function () {
    selectedDeviceId = $(this).val();
    if (codeReader) {
        codeReader.reset();
        initScanner();
    }
});

// Function to initialize the barcode scanner
function initScanner() {
    codeReader
        .listVideoInputDevices()
        .then(videoInputDevices => {
            videoInputDevices.forEach(device =>
                console.log(`${device.label}, ${device.deviceId}`)
            );

            if (videoInputDevices.length > 0) {
                if (selectedDeviceId == null) {
                    // Choose the first available camera by default
                    selectedDeviceId = videoInputDevices[0].deviceId;
                }

                // Update the dropdown with available cameras
                if (videoInputDevices.length >= 1) {
                    sourceSelect.html('');
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option');
                        sourceOption.text = element.label;
                        sourceOption.value = element.deviceId;
                        if (element.deviceId == selectedDeviceId) {
                            sourceOption.selected = 'selected';
                        }
                        sourceSelect.append(sourceOption);
                    });
                }

                // Start scanning for barcodes using the selected camera
                codeReader
                    .decodeOnceFromVideoDevice(selectedDeviceId, 'previewKamera')
                    .then(result => {
                        // Handle the scanned barcode result
                        console.log(result.text);
                        $("#hasilscan").val(result.text);

                        // Reset the barcode reader
                        if (codeReader) {
                            codeReader.reset();
                        }
                    })
                    .catch(err => console.error(err));
            } else {
                alert("Camera not found!");
            }
        })
        .catch(err => console.error(err));
}

// Check if the browser supports mediaDevices (camera access)
if (navigator.mediaDevices) {
    // Initialize the barcode scanner
    initScanner();
} else {
    alert('Cannot access camera.');
}
