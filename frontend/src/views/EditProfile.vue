<template>
  <div class="profile-edit-wrapper">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <!-- Header -->
          <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-success">
              <i class="bi bi-person-gear me-2"></i>Edit Profile
            </h1>
            <p class="lead text-secondary">Update your personal and professional information</p>
          </div>

          <div class="card border-0 shadow-lg">
            <div class="card-body p-4">
              <div class="row g-4">
                <!-- Left Column - Form -->
                <div class="col-lg-8 pe-lg-4">
                  <form @submit.prevent="updateProfile">
                    <!-- Personal Info Section -->
                    <div class="mb-4">
                      <h5 class="fw-bold text-success mb-3">
                        <i class="bi bi-person-lines-fill me-2"></i>Personal Information
                      </h5>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label for="name" class="form-label">Full Name</label>
                          <input v-model="user.name" type="text" class="form-control" id="name"
                            placeholder="Enter your full name" required />
                        </div>

                        <div class="col-md-6">
                          <label for="email" class="form-label">Email</label>
                          <input v-model="user.email" type="email" class="form-control" id="email"
                            placeholder="Enter your email" required disabled />
                        </div>

                        <div class="col-md-6">
                          <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1"></i>Phone Number
                          </label>
                          <input v-model="profile.phone" type="tel" class="form-control" id="phone"
                            placeholder="Enter phone number" />
                        </div>

                        <div class="col-md-6">
                          <label for="address" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>Address
                          </label>
                          <input v-model="profile.address" type="text" class="form-control" id="address"
                            placeholder="Enter your address" />
                        </div>
                      </div>
                    </div>

                    <!-- Bio Section -->
                    <div class="mb-4">
                      <label for="bio" class="form-label">
                        <i class="bi bi-card-text me-1"></i>Professional Bio
                      </label>
                      <textarea v-model="profile.bio" class="form-control" id="bio" rows="3"
                        placeholder="Tell us about your professional background"></textarea>
                    </div>

                    <!-- File Uploads Section -->
                    <div class="mb-4">
                      <h5 class="fw-bold text-success mb-3">
                        <i class="bi bi-files me-2"></i>Documents
                      </h5>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label for="profile-pic" class="form-label">
                            <i class="bi bi-person-badge me-1"></i>Profile Picture
                          </label>
                          <input type="file" class="form-control" id="profile-pic" @change="handleProfilePic"
                            accept="image/*" />
                          <div class="form-text text-muted">Max size: 5MB (JPG, PNG)</div>
                        </div>

                        <div class="col-md-6">
                          <label for="resume" class="form-label">
                            <i class="bi bi-file-earmark-text me-1"></i>Resume/CV
                          </label>
                          <input type="file" class="form-control" id="resume" @change="handleResume"
                            accept=".pdf,.doc,.docx" />
                          <div class="form-text text-muted">Max size: 10MB (PDF, DOC)</div>
                        </div>
                      </div>

                      <!-- Profile Picture Preview -->
                      <div v-if="profilePicturePreview" class="mt-3 text-center">
                        <div class="d-inline-block position-relative">
                          <img :src="profilePicturePreview" alt="Profile Preview"
                            class="img-thumbnail rounded-circle border-success"
                            style="width: 120px; height: 120px; object-fit: cover;">
                          <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            New
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-4">
                      <h5 class="fw-bold text-success mb-3">
                        <i class="bi bi-shield-lock me-2"></i>Change Password
                      </h5>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label for="password" class="form-label">New Password</label>
                          <input v-model="user.password" type="password" class="form-control" id="password"
                            placeholder="Enter new password (optional)" />
                        </div>

                        <div class="col-md-6">
                          <label for="confirm-password" class="form-label">Confirm Password</label>
                          <input v-model="user.password_confirmation" type="password" class="form-control"
                            id="confirm-password" placeholder="Confirm new password" :disabled="!user.password" />
                        </div>
                      </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                      <router-link to="/profile" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancel
                      </router-link>

                      <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save me-1"></i>Save Changes
                      </button>
                    </div>
                  </form>
                </div>

                <!-- Right Column - Info -->
                <div class="col-lg-4 ps-lg-4">
                  <div class="sticky-top" style="top: 20px;">
                    <div class="card border-0 bg-light-success">
                      <div class="card-body">
                        <div class="text-center mb-4">
                          <i class="bi bi-person-check display-4 text-success"></i>
                        </div>

                        <h5 class="fw-bold text-success mb-3">Complete Your Profile</h5>

                        <ul class="list-unstyled">
                          <li class="mb-3 d-flex">
                            <span class="me-2 text-success"><i class="bi bi-check-circle-fill"></i></span>
                            <span>Better job matching with complete information</span>
                          </li>
                          <li class="mb-3 d-flex">
                            <span class="me-2 text-success"><i class="bi bi-check-circle-fill"></i></span>
                            <span>Increased visibility to employers</span>
                          </li>
                          <li class="mb-3 d-flex">
                            <span class="me-2 text-success"><i class="bi bi-check-circle-fill"></i></span>
                            <span>Faster application processes</span>
                          </li>
                          <li class="mb-3 d-flex">
                            <span class="me-2 text-success"><i class="bi bi-check-circle-fill"></i></span>
                            <span>Professional appearance to recruiters</span>
                          </li>
                        </ul>

                        <div class="alert alert-success mt-4">
                          <i class="bi bi-lightbulb me-2"></i>
                          <strong>Tip:</strong> Keep your profile updated to get the best opportunities.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  data ()
  {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      profile: {
        bio: '',
        phone: '',
        address: ''
      },
      profilePicture: null,
      profilePicturePreview: null,
      resume: null
    };
  },
  setup ()
  {
    const toast = useToast();
    return { toast };
  },
  methods: {
    handleProfilePic ( e )
    {
      this.profilePicture = e.target.files[ 0 ];
      if ( this.profilePicture )
      {
        const reader = new FileReader();
        reader.onload = ( event ) =>
        {
          this.profilePicturePreview = event.target.result;
        };
        reader.readAsDataURL( this.profilePicture );
      }
    },
    handleResume ( e )
    {
      this.resume = e.target.files[ 0 ];
    },
    async updateProfile ()
    {
      if ( this.user.password && this.user.password !== this.user.password_confirmation )
      {
        this.toast.error( 'Passwords do not match' );
        return;
      }

      const formData = new FormData();
      formData.append( 'name', this.user.name );
      formData.append( 'email', this.user.email );
      formData.append( 'bio', this.profile.bio );
      formData.append( 'phone', this.profile.phone );
      formData.append( 'address', this.profile.address );

      if ( this.user.password )
      {
        formData.append( 'password', this.user.password );
        formData.append( 'password_confirmation', this.user.password_confirmation );
      }

      if ( this.profilePicture )
      {
        formData.append( 'profile_picture', this.profilePicture );
      }

      if ( this.resume )
      {
        formData.append( 'resume', this.resume );
      }

      try
      {
        const response = await axios.post( '/api/profile', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        } );

        this.toast.success( 'Profile updated successfully!' );
        this.$router.push( '/profile' );
      } catch ( error )
      {
        console.error( 'Update error:', error );
        const errorMsg = error.response?.data?.message || 'Failed to update profile';
        this.toast.error( errorMsg );
      }
    }
  },
  async mounted ()
  {
    try
    {
      const response = await axios.get( '/api/profile' );
      this.profile = response.data.profile || {};
      this.user = {
        ...this.user,
        name: response.data.user?.name || '',
        email: response.data.user?.email || ''
      };
    } catch ( error )
    {
      console.error( 'Error fetching profile:', error );
      this.toast.error( 'Failed to load profile data' );
    }
  }
};
</script>

<style scoped>
.profile-edit-wrapper {
  background-color: #f8f9fa;
}

/* Custom color classes */
.text-success {
  color: #34A853 !important;
}

.bg-success {
  background-color: #34A853 !important;
}

.border-success {
  border-color: #34A853 !important;
}

.btn-success {
  background-color: #34A853;
  border-color: #34A853;
}

.btn-success:hover {
  background-color: #2d9145;
  border-color: #2d9145;
}

.bg-light-success {
  background-color: rgba(52, 168, 83, 0.05);
}

.alert-success {
  background-color: rgba(52, 168, 83, 0.1);
  border-color: rgba(52, 168, 83, 0.2);
  color: #2d9145;
}

/* Form styling */
.form-control:focus {
  border-color: #34A853;
  box-shadow: 0 0 0 0.25rem rgba(52, 168, 83, 0.25);
}

.card {
  border-radius: 12px;
  overflow: hidden;
}

.border-top {
  border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .pe-lg-4 {
    padding-right: 0 !important;
    border-right: none !important;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  }

  .ps-lg-4 {
    padding-left: 0 !important;
  }

  .sticky-top {
    position: relative !important;
  }
}
</style>