<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDI(Sunni Dawate Islami)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<section class="h-100 h-custom" style="background-color: #8fc4b7;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-8 col-xl-6">
        <div class="card rounded-3">
          <img src="image.jpg" class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem;" alt="Sample photo">

          <div class="card-body p-4 p-md-5">
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Registration Form</h3>

            <form action="{{ route('form.store') }}" method="POST" id="form">
                @csrf
                <div class="row">
                    <!-- Name input field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="name">Name / नाम / نام <span style="color: red;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Age input field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="age">Age / आयु / عمر </label>
                            <input type="text" name="age" id="age" class="form-control @error('age') is-invalid @enderror">
                        </div>
                        @error('age')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Gender select field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="gender">Gender / लिंग / جنس</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="" disabled selected>Select Gender </option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact input field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="contact">Contact / फ़ोन / فون <span style="color: red;">*</span></label>
                            <input type="tel" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror">
                        </div>
                        @error('contact')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Email input field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="email">Email / ईमेल / ای میل</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- City select field -->
                    <div class="col-md-6 mb-4">
                        <div data-mdb-input-init class="form-outline">
                        <label class="form-label" for="city">City / शहर / شہر<span style="color: red;">*</span></label>
                            <select name="city" id="city" class="form-control @error('city') is-invalid @enderror">
                                <option value="" disabled selected>Select a City </option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Note textarea field -->
                <div class="mb-4">
                    <label class="form-label" for="note">Note / नोट / نوٹ</label>
                    <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"></textarea>
                    @error('note')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success" id="submit-btn">Save</button>
                </div>
            </form>
          </div>
        </div>
      </div>
 </div>
  <footer class="mt-2 pt-2 pb-2" style="color: white; text-align: center;">
    <p class="mt-2">
        &#xA9; <?=date("Y") ?> All Rights Reserved by Sunni Dawate Islami (SDI).
   <br>
        Developed By 
        <a href="https://magnusideas.com" target="_blank" style="color:rgb(8, 58, 122); text-decoration: none;">Magnus Ideas Pvt. Ltd.</a>
    </p>
  </footer>
  </div>

</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
