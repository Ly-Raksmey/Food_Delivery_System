<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="aboutModalLabel"><i class="bi bi-info-circle me-2"></i>About Us</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('about.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="aboutName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="aboutName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="aboutEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="aboutEmail" name="email" required>
                        </div>
                        <div class="col-12">
                            <label for="aboutMessage" class="form-label">Your Inquiry</label>
                            <textarea class="form-control" id="aboutMessage" name="message" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Send Inquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
