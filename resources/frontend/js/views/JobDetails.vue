<template>
    <div class="">
        <!-- Header Start -->
        <div class="py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 slideInDown">Job Details</h1>
            </div>
        </div>
        <!-- Header End -->
        <!-- Job Detail Start -->
        <div class="container-xxl py-5 shadow p-4 rounded">
            <div class="container">
                <div class="row gy-5 gx-4">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-5">
                            <img class="flex-shrink-0 img-fluid border rounded" :src="storageImage(job.image)" alt="" style="width: 80px; height: 80px;">
                            <div class="text-start ps-4">
                                <h3 class="mb-3">{{ job.position }}</h3>
                                <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ job.address }}</span>
                                <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>{{ job.job_type == 1 ? 'Remote' : (job.job_type == 2 ? 'Full Time' : (job.job_type == 3 ? 'Part Time' : 'Not Specified')) }}</span>
                                <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>{{ job.salary || 'Negotiable'}}</span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <p v-html="job.details"></p>
                        </div>

                        <div>
                            <div v-if="isAuthenticated">
                                <h4 class="mb-4">Apply For The Job</h4>
                                <form @submit="submitApplication()">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <input type="text" class="form-control" placeholder="Your Name" v-model="fromData.name" readonly>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="email" class="form-control" placeholder="Your Email" v-model="fromData.email" readonly>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="number" class="form-control" placeholder="Your Phone" v-model="fromData.phone" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <select class="form-control" v-model="fromData.address" required>
                                                <template v-for="data in requireData.district">
                                                    <option :value="data.name">{{data.name}}</option>
                                                </template>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Portfolio/LinkedIn/GitHub</label>
                                            <input type="url" class="form-control" placeholder="Portfolio Website" v-model="fromData.portfolio">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cover Letter</label>
                                            <editor v-model="fromData.coverLetter" name="coverLetter" :init="tinymceInit"/>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Upload your resume</label>
                                                <div @click="clickFileField('imageField')" class="image_upload" :style="{ 'background-image': 'url(' + publicImage('images/uploading.avif') + ')' }">
                                                    <template v-if="fromData.image">
                                                        <template v-if="isPDF(fromData.image)">
                                                            <img src="https://i.ibb.co.com/7NzrJtr/4208479.png" style="width: 100px; height: 100px" alt="PDF Icon">
                                                        </template>
                                                        <template v-else>
                                                            <img class="photo" :src="storageImage(fromData.image)" style="width: 100px; height: 100px" alt="Image">
                                                        </template>
                                                    </template>
                                                </div>
                                                <input @change="uploadImage($event, fromData, 'image')" type="file" name="image" id="imageField" class="file_field" accept=".pdf,image" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div :class="{'': hasApplied.includes(job.id), '': !hasApplied.includes(job.id)}">
                                             <span class="btn btn-primary w-100" v-if="hasApplied.includes(job.id)">
                                                <i class="fa fa-check"></i> Applied
                                                </span>
                                                <p v-else>
                                                    <button class="btn btn-primary w-100" :disabled="countdownTime === 'Time is up!' || job.vacancy === 0">Apply Now</button>
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div v-else>
                                <a :href="`/seekerlogin?next_url=${$route.fullPath}`" class="nav-link">
                                    <button class="btn btn-primary w-100">{{$t('applied')}}</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div v-if="job" class="bg-light rounded p-5 mb-4">
                            <h4 class="mb-4">Job Summary</h4>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Published On: {{ formatDate(job.created_at) }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Job Category: {{ job.category.name }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Vacancy: {{ job.vacancy }}</p>
                            <p>
                                <i class="fa fa-angle-right text-primary me-2"></i>
                                Job Nature: {{ job.job_type == 1 ? 'Full Time' : (job.job_type == 2 ? 'Part Time' : 'Not Specified') }}
                            </p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Salary: {{ job.salary || 'Negotiable' }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Location: {{ job.address }}</p>
                            <p class="m-0" v-if="countdownTime && countdownTime !== 'Time is up!'"><i class="fa fa-angle-right text-primary me-2"></i>Date Line: {{ formatDate(job.date_time) }}</p>

                            <div v-if="countdownTime">
                                <h5>Time Left: {{ countdownTime }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Job Detail End -->
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "JobDetails",
        props: ['id'],
        data() {
            return {
                job: {
                    category: {},
                },
                isAuthenticated: false,
                countdownTime: "",
                tinymceInit: {
                    height: 300,
                    menubar: false,
                    plugins: 'link image code',
                    toolbar: 'undo redo | styleselect | bold italic | link image | code',
                },
                saveds: [],
                hasApplied: [],
                timer: null,
            };
        },
        mounted() {
            this.getJobDetails();
            this.getRequiredData(['district']);
            this.authData();
        },
        methods: {
            async getJobDetails() {
                const _this = this;
                try {
                    const response = await axios.get(`/api/frontend/detailsData/${this.id}`);
                    if (response.data && response.data.result) {
                        _this.job = response.data.result.job;
                        _this.hasApplied = response.data.result.application.map(app => app.job_id);
                        this.startCountdown();
                    } else {
                        _this.error = "No Job details found.";
                    }
                } catch (error) {
                    _this.error = "Failed to load Job details.";
                }
            },

            startCountdown() {
                let endTime = new Date(this.job.date_time);
                endTime.setHours(0, 0, 0, 0);
                if (new Date().getTime() > endTime.getTime()) {
                    endTime.setDate(endTime.getDate() + 1);
                }
                this.timer = setInterval(() => {
                    this.updateCountdown(endTime.getTime());
                }, 1000);
            },



            async submitApplication() {
                this.fromData.job_id = this.job.id;
                this.fromData.seeker_id = this.seeker.id;

                try {
                    const res = await axios.post('/api/application', this.fromData);
                    if (parseInt(res.data.status) === 2000) {
                        this.$toast.success("Application Submitted Successfully");
                        this.saveds.push(this.job.id);
                    }
                } catch (error) {
                    if (error.response) {
                        this.error = "No Application found.";
                        this.$toast.error(this.error);
                    } else {
                        this.$toast.error("An unexpected error occurred.");
                    }
                }
            }
        },

    };
</script>

<style scoped>
    .image_upload img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 4px;
    }
    .image_upload {
        height: 120px;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        cursor: pointer;
        border: 2px dashed #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .photo {
        height: 100%;
        width: 100%;
        border-radius: 4px;
    }
    #imageField {
        display: none;
    }
</style>
