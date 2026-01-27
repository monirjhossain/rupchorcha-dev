@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Product Bulk Import</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="bulkImportForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="import_file">Upload CSV File</label>
                    <input type="file" name="import_file" id="import_file" class="form-control" accept=".csv" required>
                </div>
                <button type="submit" class="btn btn-primary" id="importBtn">Import Products</button>
            </form>
            <div id="progressContainer" class="mt-4" style="display:none;">
                <label>Import Progress</label>
                <div class="progress">
                    <div id="importProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
                </div>
                <div id="progressStatus" class="mt-2"></div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('bulkImportForm');
                const progressContainer = document.getElementById('progressContainer');
                const progressBar = document.getElementById('importProgressBar');
                const progressStatus = document.getElementById('progressStatus');
                const importBtn = document.getElementById('importBtn');
                let pollingInterval = null;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const fileInput = document.getElementById('import_file');
                    if (!fileInput.files.length) return;
                    const formData = new FormData(form);
                    importBtn.disabled = true;
                    progressContainer.style.display = 'block';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    progressStatus.textContent = 'Uploading and importing...';

                    fetch("{{ route('products.bulkImport') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Start polling for progress
                            pollProgress();
                        } else {
                            progressStatus.textContent = 'Import failed.';
                            importBtn.disabled = false;
                        }
                    })
                    .catch(() => {
                        progressStatus.textContent = 'Import failed.';
                        importBtn.disabled = false;
                    });
                });

                function pollProgress() {
                    if (pollingInterval) clearInterval(pollingInterval);
                    pollingInterval = setInterval(() => {
                        fetch("{{ route('products.bulkImportProgress') }}")
                        .then(res => res.json())
                        .then(progress => {
                            let percent = progress.percent || 0;
                            progressBar.style.width = percent + '%';
                            progressBar.textContent = percent + '%';
                            if (progress.done) {
                                clearInterval(pollingInterval);
                                progressStatus.textContent = 'Import complete!';
                                importBtn.disabled = false;
                            } else {
                                progressStatus.textContent = `Imported ${progress.current} of ${progress.total} products...`;
                            }
                        });
                    }, 1000);
                }
            });
            </script>
            <hr>
            <p class="mt-3">Download a sample CSV: <a href="{{ route('products.bulkImportSample') }}" class="btn btn-sm btn-info">Download Sample</a></p>
            <hr>
            <div class="alert alert-info mt-3">
                <strong>CSV Import Instructions:</strong><br>
                <ul class="mb-1">
                    <li><strong>Required fields:</strong> <code>name</code>, <code>sku</code>, <code>price</code>, <code>category</code>, <code>brand</code>, <code>type</code></li>
                    <li><strong>Optional fields:</strong> <code>sale_price</code>, <code>cost_price</code>, <code>stock_quantity</code>, <code>main_image</code></li>
                    <li>For <code>category</code> and <code>brand</code>, use the exact name (not ID) as in your system.</li>
                    <li>Example CSV columns: <code>name,sku,price,sale_price,cost_price,category,brand,stock_quantity,main_image,type</code></li>
                    <li>Download the sample CSV for correct format.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
