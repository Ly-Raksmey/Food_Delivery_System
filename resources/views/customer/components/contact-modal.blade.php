<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="contactModalLabel"><i class="bi bi-envelope me-2"></i>Contact Us</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="contactName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="contactName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contactEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contactEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contactPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="contactPhone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="contactSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="contactSubject" name="subject">
                        </div>
                        <div class="col-12">
                            <label for="contactMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="contactMessage" name="message" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
