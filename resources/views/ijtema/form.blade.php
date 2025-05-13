<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create</h1>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('form.store') }}" method="POST" id="form">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" required>
                        <div class="text-danger" id="contact-error" style="display: none;">This number is already registered.</div>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select name="city" class="form-control" id="city-dropdown" required>
                            <option value="" disabled selected>Select a city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ $city->id == 101 ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" required></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('formlist') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS --> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for duplicate contact validation -->
    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault();

            const contact = document.getElementById('contact').value;
            const contactError = document.getElementById('contact-error');

            fetch(`/check-contact?contact=${contact}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        contactError.style.display = 'block';
                    } else {
                        contactError.style.display = 'none';
                        this.submit();
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
