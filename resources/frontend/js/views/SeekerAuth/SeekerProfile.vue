<template>
<div class="container mt-5">
<!-- Profile Section -->
<div class="row">
<div class="col-md-4">
<div class="card">
    <div class="card-body text-center">
        <img style="width: 100px; height: 100px; border: solid 5px; border-radius: 50px"
             :src="storageImage(seeker.image)"
             class="img-fluid rounded-circle mx-auto d-block" alt="Profile Picture">
        <h4 class="mt-3">{{ seeker.name }}</h4>
        <h6 class="pt-2">{{$t('bio')}}</h6>
        <p class="text-muted">{{ seeker.bio }}</p>
        <hr>
        <h6>{{$t('experience')}}</h6>
        <p class="text-muted">{{ seeker.experience }}</p>
        <hr>
        <h6>{{$t('skill')}}:</h6>
        <p v-if="parsedSkills">
                <label class="badge bg-primary mx-1" v-for="(Skills, index) in parsedSkills" :key="index">{{ Skills }}</label>
        </p>
        <hr>
        <h6>{{$t('education')}}:</h6>
        <p v-if="parsedEducation">
                <label class="badge bg-primary mx-1" v-for="(educat, index) in parsedEducation" :key="index">{{ educat }}</label>
        </p>

        <hr>
        <h6>Contact Info</h6>
        <p><strong>{{$t('email')}}:</strong> {{ seeker.email }}</p>
        <p><strong>{{$t('phone')}}:</strong> +88{{ seeker.phone }}</p>
        <p><strong>{{$t('address')}}:</strong> {{ seeker.address }}</p>
    </div>
</div>
</div>
<!-- Dashboard Sections -->
<div class="col-md-8">
<!-- Menu Tabs -->
<ul class="nav nav-tabs" id="profileTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="applied-jobs-tab" data-bs-toggle="tab" href="#applied-jobs"
           role="tab" aria-controls="applied-jobs" aria-selected="true">{{ $t('applied_jobs') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="saved-jobs-tab" data-bs-toggle="tab" href="#saved-jobs" role="tab"
           aria-controls="saved-jobs" aria-selected="false">{{ $t('saved_jobs') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="update-profile-tab" data-bs-toggle="tab" href="#update-profile"
           role="tab" aria-controls="update-profile" aria-selected="false"
           @click="goToUpdateProfile">{{ $t('update_profile') }}</a>
    </li>
</ul>
<!-- Tab Content -->
<div class="tab-content mt-3" id="profileTabContent">
    <!-- Applied Jobs Section -->
    <div class="tab-pane fade show active" id="applied-jobs" role="tabpanel"
         aria-labelledby="applied-jobs-tab">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">{{ $t('job_title') }}</th>
                        <th scope="col">{{ $t('application_date') }}</th>
                        <th scope="col">{{ $t('location') }}</th>
                        <th scope="col">{{ $t('status') }}</th>
                        <th scope="col">{{ $t('interview') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="apply in applications" :key="apply.id">
                        <td>{{ apply.job.position }}</td>
                        <!--                                        <td>{{ new Date(apply.created_at).toLocaleDateString() }}</td>-->
                        <td>{{formatDate(apply.created_at)}}</td>

                        <td>{{ apply.job.address }}</td>
                        <td>
                        <span :class="{
                            'bg-primary p-1': apply.application_status == 1,
                            'bg-danger p-1': apply.application_status == 2,
                            'bg-warning p-1': apply.application_status == 0
                        }">
                            <label class="badge text-white mx-1">{{ apply.application_status == 0 ? 'Pending' : apply.application_status == 1 ? 'Accepted' : 'Rejected' }}</label>
                        </span>
                        </td>


                        <td>
                            <router-link :to="{ name: 'interviewtable', params: { id: apply.id } }"
                                         class="btn btn-info btn-sm">
                                {{ $t('interviewSchedule') }}
                            </router-link>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Saved Jobs Section -->
    <div class="tab-pane fade" id="saved-jobs" role="tabpanel" aria-labelledby="saved-jobs-tab">
        <div class="overflow-auto" style="max-height: 700px;">
            <div v-for="job in savedJobs" :key="job.id">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between">
                        <div class="job-details">
                            <div class="job-title-text mb-3">
                                <router-link :to="{ name: 'Details', params: { id: job.job_id }}"
                                             class="job-link text-decoration-none">
                                    <h5 class="card-title text-primary">
                                        <i class="bi bi-briefcase"></i>
                                        {{truncateString(job.job.position, 50)}}
                                    </h5>
                                </router-link>
                            </div>
                            <a class="comp-name-text mb-2">
                                <i class="bi bi-geo-alt"></i>
                                <strong>{{ $t('address') }}:</strong>
                                <span class="text-muted">
                            {{ job.job.address }}
                            </span>
                            </a>
                        </div>
                        <div class="logo-container">
                            <img style="height: 80px" :src="storageImage(job.job.image)" alt="Company Logo"
                                 class="company-logo"/>
                        </div>
                    </div>
                    <div class="d-flex gap-2 p-3">
                        <router-link :to="{ name: 'Details', params: { id: job.job_id }}"
                                     class="btn btn-primary">{{ $t('apply_now') }}
                        </router-link>
                        <button @click="deleteJob(job.id)" class="btn btn-danger">{{ $t('remove') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Update Profile Section -->
    <div class="tab-pane fade" id="update-profile" role="tabpanel" aria-labelledby="update-profile-tab">
        <div class="card shadow-sm">
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">{{$t('updateProfile')}}</h4>
                    </div>
                    <div class="card-body">
                        <form id="updateProfileForm" @submit="submitFromData(fromData)">
                            <div class="row">
                                <!-- Name and Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">{{$t('name')}}</label>
                                    <input type="text" class="form-control" id="name" v-model="fromData.name" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{$t('email')}}</label>
                                    <input type="email" class="form-control" id="email" v-model="fromData.email" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Phone and Address -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{$t('phone')}}</label>
                                    <input type="text" class="form-control" id="phone" v-model="fromData.phone" placeholder="Enter your phone number">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">{{$t('address')}}</label>
                                    <select class="form-select" id="address" v-model="fromData.address">
                                        <option value="" disabled>Select your address</option>
                                        <template v-for="data in requireData.district" >
                                            <option :value="data.name">{{ data.name }}</option>
                                        </template>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6 mb-3  ">
                                    <label  class="form-label">{{$t('skill')}}</label>
                                    <multiselect
                                            v-model="fromData.skills"
                                            :options="skillname"
                                            :multiple="true"
                                            placeholder="Choose Skill"

                                    ></multiselect>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $t('education') }}</label>
                                    <multiselect
                                            v-model="fromData.education"
                                            :options="educations"
                                            :multiple="true"
                                            placeholder="Choose Education Levels"

                                    ></multiselect>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="experience" class="form-label">{{$t('experience')}}</label>
                                <input type="text" class="form-control" id="experience" v-model="fromData.experience">
                            </div>
                            <!-- Bio -->
                            <div class="mb-3">
                                <label for="bio" class="form-label">{{ $t('bio') }}</label>
                                <textarea class="form-control" id="bio" v-model="fromData.bio" rows="4"></textarea>
                            </div>


                            <!-- Profile Picture Upload -->
                            <div class="mb-3">
                                <label class="form-label">{{$t('uploadImage')}}</label>
                                <div @click="clickFileField('imageField')" class="image_upload"
                                     :style="{ 'background-image': 'url(' + publicImage('images/uploading.avif') + ')' }">
                                    <template v-if="fromData.image !== undefined">
                                        <img class="photo img-thumbnail" :src="storageImage(fromData.image)" style="width: 150px;">
                                    </template>
                                </div>
                                <input @change="uploadImage($event, fromData, 'image')" type="file" name="image" id="imageField" class="file_field">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-block">{{ $t('update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
`
</div>
</div>
</div>
    <a data-toggle="modal" data-target="#chatModal" href="" title="Support Desk" style="margin: 0 0 20px 0 !important;
height: 52px; min-width: 52px; padding: 10px 0px 0px 10px; position: fixed !important; bottom: 10px; right: 20px; z-index: 999999999 !important; background-color: #00E785;
    box-shadow: 4px 5px 10px rgba(0, 0, 0, 0.4); border-radius: 100px" >
        <svg xmlns="http://www.w3.org/2000/svg"
             shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality"
             fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 512.02"><path d="M106.48 511.85c-2.07-.27-4.07-.86-5.93-1.72h-.01c-.34-.17-.69-.34-1.03-.52h-.01c-.25-.14-.51-.28-.76-.43h-.01c-.14-.08-.28-.17-.42-.26-.91-.56-1.77-1.19-2.58-1.89-.94-.8-1.8-1.69-2.58-2.66v-.01h-.01c-.77-.95-1.46-1.99-2.05-3.08v-.01h-.01c-.21-.39-.41-.79-.6-1.2v-.01c-.37-.8-.68-1.62-.94-2.48v-.01h-.01c-.06-.19-.11-.39-.17-.58v-.02c-.09-.34-.18-.69-.25-1.03v-.01c-.03-.13-.06-.27-.09-.41v-.02c-.07-.33-.12-.66-.17-1v-.03h-.01c-.03-.21-.05-.43-.08-.64v-.05c-.04-.3-.06-.6-.08-.91v-.12h-.01l-.03-.62-.05-1.61-.95-30.62-.41-13.33c-.13-5.64-2.69-10.89-6.85-14.6-.39-.35-.78-.7-1.17-1.06l-.08-.07v-.02h-.01c-.28-.25-.57-.51-.85-.77h-.01l-.08-.07v-.02h-.01l-.07-.06v-.02h-.03l-.92-.86-.09-.08v-.01l-.08-.07v-.01h-.02c-.97-.91-1.94-1.83-2.91-2.75v-.01l-.08-.07v-.01h-.01l-.08-.08v-.01h-.01l-.07-.07v-.01h-.02l-.07-.07v-.02h-.02l-.07-.06v-.03h-.02l-.06-.05v-.03h-.03a239.65 239.65 0 01-6.16-6.19h-.01l-.08-.09c-.49-.51-.98-1.02-1.46-1.54v-.01h-.01l-.08-.08-.08-.09h-.01l-.94-1v-.03h-.03l-.06-.07v-.02h-.02l-.06-.07v-.02h-.01l-.08-.07v-.01c-.32-.34-.63-.68-.95-1.02v-.01l-.08-.09c-.55-.6-1.09-1.2-1.64-1.8-.2-.23-.4-.45-.6-.67v-.02h-.02l-.06-.08v-.01h-.01l-.08-.08v-.01l-.51-.57v-.03h-.03l-.06-.07v-.01h-.01l-.59-.68v-.01h-.01c-.2-.23-.4-.45-.6-.68v-.01l-1.11-1.29h-.01c-.14-.17-.28-.34-.42-.5v-.02h-.01l-.51-.59v-.01h-.01c-.14-.16-.28-.33-.42-.5v-.01h-.01l-.08-.09-.34-.41V407h-.02l-.07-.08v-.01c-.11-.13-.23-.27-.34-.41v-.02h-.01l-.42-.5v-.01h-.01c-.63-.77-1.26-1.54-1.88-2.31v-.01h-.01l-.34-.42v-.01h-.01c-.11-.14-.22-.28-.33-.41v-.02h-.02l-.07-.09-.25-.32v-.02h-.02l-.33-.42v-.01h-.01l-.33-.43h-.01c-.31-.4-.63-.8-.94-1.21-.29-.37-.57-.74-.86-1.12l-.26-.33v-.01l-.25-.33v-.02h-.01l-.25-.32v-.02h-.02l-.24-.32v-.02h-.02l-.24-.32v-.03h-.02l-.06-.08h-.01l-.17-.23v-.03h-.02l-.23-.32v-.02h-.02c-.66-.89-1.32-1.78-1.96-2.67l-.43-.59v-.01h-.01c-.14-.2-.28-.39-.42-.59v-.02h-.01c-.09-.11-.17-.22-.25-.34l-.17-.24v-.02h-.02l-.41-.59v-.01h-.01c-.2-.28-.4-.56-.59-.85v-.01h-.01c-.14-.2-.29-.4-.42-.6h-.01c-.31-.46-.63-.92-.94-1.38l-.17-.25v-.01h-.01l-.17-.24v-.01h-.01l-.16-.24v-.02h-.01l-.16-.23v-.03h-.02c-.86-1.28-1.72-2.57-2.56-3.86v-.01l-.6-.93V383h-.01l-.16-.24v-.01h-.01l-.17-.26c-.08-.14-.17-.27-.25-.4v-.03H38l-.16-.24v-.02h-.01l-.16-.26c-.08-.14-.17-.27-.26-.41v-.02h-.01l-.16-.25v-.01l-.43-.68v-.01c-.46-.74-.92-1.48-1.37-2.23l-.26-.42v-.01h-.01c-.08-.14-.17-.28-.25-.42v-.01h-.01c-.08-.14-.17-.28-.25-.41v-.02h-.01c-.08-.14-.16-.28-.25-.42v-.01l-.25-.43h-.01c-.49-.83-.97-1.66-1.46-2.5-.11-.2-.23-.4-.34-.6l-.35-.6-.08-.15v-.02h-.01c-.08-.14-.17-.29-.25-.43l-.09-.15v-.02h-.01l-.33-.6v-.01l-.09-.14v-.03h-.01l-.33-.59v-.01c-.15-.26-.29-.51-.43-.77v-.01c-.35-.62-.69-1.25-1.03-1.88v-.01l-.09-.15v-.02h-.01c-.16-.31-.34-.63-.5-.94l-.09-.16v-.02h-.01l-.07-.14v-.03h-.02l-.07-.13v-.04h-.02c-.2-.37-.39-.75-.58-1.12l-.09-.16v-.01l-.08-.16v-.01h-.01c-.58-1.12-1.15-2.23-1.72-3.36l-.08-.17-.09-.17-.08-.17h-.01l-.08-.17v-.01l-.09-.16v-.01l-.08-.17c-.67-1.36-1.33-2.73-1.98-4.11v-.02h-.01l-.08-.16v-.01c-.14-.3-.29-.6-.43-.9v-.05h-.02l-.06-.13v-.04h-.02l-.07-.15v-.02h-.01l-.07-.17c-.12-.25-.23-.5-.35-.76v-.02h-.01l-.07-.17c-.15-.31-.29-.62-.43-.94h-.01c-.23-.52-.46-1.03-.68-1.54v-.01h-.01c-.11-.26-.22-.52-.34-.77v-.01c-.08-.19-.17-.39-.25-.58v-.02h-.01c-.08-.19-.17-.38-.25-.57v-.03h-.02l-.07-.17c-.06-.14-.11-.27-.17-.4V348h-.01c-.09-.2-.17-.39-.25-.58v-.03h-.01l-.25-.58v-.02c-.09-.2-.17-.39-.25-.59v-.01h-.01c-.08-.2-.17-.4-.25-.6-.32-.77-.64-1.55-.95-2.32l-.17-.42v-.01h-.01l-.16-.42v-.01h-.01c-.05-.14-.11-.29-.16-.43h-.01l-1.2-3.09v-.01c-.09-.23-.17-.45-.26-.68v-.01l-.26-.67v-.02l-.08-.21v-.04h-.02l-.15-.41v-.02h-.01l-.08-.21v-.05h-.02c-.05-.14-.1-.29-.15-.43l-.09-.23v-.03h-.01l-.24-.69-.09-.23v-.03h-.01c-.26-.71-.51-1.43-.76-2.14v-.01h-.01l-.08-.24v-.02l-.09-.23v-.02l-.08-.23V332h-.01c-.17-.49-.34-.97-.51-1.45v-.01l-.08-.25v-.01h-.01l-.08-.25v-.01l-.09-.25v-.01l-.08-.25v-.01l-.09-.25-.08-.26h-.01l-.08-.26-.09-.25v-.01l-.08-.25-.09-.26-.08-.26h-.01c-.17-.54-.34-1.08-.51-1.61v-.03h-.01l-.08-.24v-.01c-.09-.27-.17-.54-.25-.81v-.05h-.02l-.07-.22v-.04h-.02l-.07-.23v-.03h-.01l-.07-.25v-.01h-.01c-.05-.18-.11-.37-.17-.55v-.05h-.01l-.07-.24v-.02H11c-.11-.37-.22-.74-.34-1.11v-.01c-.26-.87-.52-1.75-.77-2.63v-.03h-.01l-.16-.56v-.04h-.01l-.08-.26-.08-.3v-.05h-.02l-.07-.25c-.03-.11-.06-.21-.09-.31v-.04h-.01c-.05-.19-.1-.39-.16-.59v-.01l-.08-.3v-.05H9.1l-.16-.58v-.02c-.2-.74-.4-1.48-.6-2.23l-.08-.32v-.03h-.01l-.08-.31v-.03h-.01l-.08-.3v-.04h-.01l-.07-.29v-.06h-.02l-.07-.27v-.07h-.02l-.06-.26-.09-.34v-.01l-.09-.34c-.26-1.05-.52-2.11-.77-3.17v-.01c-.09-.37-.17-.74-.26-1.1v-.02c-.06-.24-.11-.48-.17-.72v-.05h-.01l-.08-.32v-.03a4.18 4.18 0 01-.08-.34v-.09h-.02l-.07-.3v-.04h-.01c-.02-.12-.05-.24-.07-.36v-.07h-.02l-.07-.32v-.03h-.01l-.16-.76v-.01c-.24-1.08-.47-2.17-.69-3.26v-.01l-.09-.41v-.02l-.08-.4v-.03h-.01c-.02-.13-.05-.26-.08-.39v-.04h-.01c-.02-.14-.05-.27-.07-.41v-.02h-.01c-.05-.29-.11-.59-.17-.88v-.06h-.01l-.07-.39v-.04h-.01l-.08-.41v-.02l-.08-.43v-.01c-.12-.62-.23-1.25-.35-1.89l-.08-.48v-.03h-.01l-.08-.46v-.06h-.01l-.08-.42v-.1h-.01c-.02-.13-.05-.26-.07-.39v-.12h-.02c-.03-.13-.05-.27-.07-.4v-.03l-.08-.49v-.03h-.01l-.08-.5v-.01l-.09-.51v-.01c-.14-.91-.29-1.83-.43-2.74v-.01l-.08-.57v-.03h-.01l-.08-.54v-.07h-.01c-.02-.16-.05-.33-.07-.5v-.1h-.02c-.02-.17-.05-.35-.07-.52v-.08h-.01l-.08-.55v-.05l-.08-.57V284H2.4l-.08-.59v-.01c-.06-.43-.11-.85-.17-1.29-.06-.46-.11-.92-.17-1.39v-.07h-.01l-.08-.62v-.07h-.01c-.02-.21-.05-.42-.07-.63v-.14h-.02c-.03-.2-.05-.41-.07-.61v-.08h-.01c-.03-.23-.05-.47-.08-.7v-.07l-.08-.71v-.07h-.01c-.06-.51-.11-1.02-.16-1.53v-.02h-.01c-.02-.29-.05-.58-.08-.87v-.07h-.01l-.08-.81v-.14h-.01c-.02-.25-.05-.5-.07-.75v-.19H1.1c-.03-.26-.05-.52-.07-.77v-.18h-.01c-.03-.28-.05-.56-.07-.84v-.11H.94c-.03-.3-.06-.61-.08-.92v-.02c-.06-.75-.12-1.51-.17-2.26v-.15H.68c-.03-.35-.05-.71-.08-1.06v-.23H.59c-.03-.37-.05-.73-.07-1.1v-.28H.5c-.03-.4-.05-.8-.07-1.21v-.08l-.09-1.46c-.03-.63-.05-1.27-.08-1.91v-.33H.24c-.02-.6-.05-1.21-.07-1.82v-.41H.16c-.03-.78-.05-1.56-.07-2.34v-.07H.08a269.305 269.305 0 01.61-25.89v-.01c.47-6.62 1.19-13.16 2.15-19.6v-.01c.26-1.79.56-3.56.86-5.32v-.01c.27-1.58.56-3.15.86-4.72.09-.46.17-.92.26-1.38.45-2.33.94-4.66 1.46-6.97v-.01c.43-1.93.89-3.84 1.37-5.75v-.01c.42-1.64.85-3.27 1.29-4.89v-.01c.12-.42.23-.85.35-1.28v-.01c.52-1.87 1.07-3.73 1.63-5.58v-.01a255.185 255.185 0 013.61-10.92h.01c.72-2.02 1.46-4.02 2.23-6.02.86-2.22 1.74-4.43 2.66-6.62h.01v-.01c1.47-3.48 3.02-6.92 4.64-10.31 1.88-3.93 3.86-7.81 5.94-11.61.87-1.59 1.76-3.17 2.66-4.73v-.01c1.07-1.85 2.16-3.68 3.27-5.5.82-1.33 1.66-2.64 2.5-3.95 2.39-3.74 4.88-7.42 7.48-11.01 2.3-3.19 4.68-6.31 7.14-9.38 2.36-2.95 4.8-5.85 7.31-8.68v-.01C106.39 32.65 175.06.67 253.51 0h4.98c73.7.62 138.78 28.91 184.55 75.6 4.33 4.43 8.49 9.02 12.47 13.76 1.19 1.42 2.37 2.86 3.53 4.3a239.77 239.77 0 0114.1 19.61v.01c2.27 3.51 4.45 7.1 6.54 10.74v.01c.78 1.36 1.56 2.73 2.32 4.12v.01c3.06 5.59 5.9 11.33 8.52 17.19v.01c.76 1.71 1.51 3.42 2.23 5.15v.01c.71 1.68 1.4 3.36 2.07 5.07.77 1.96 1.51 3.94 2.23 5.93v.01c.69 1.87 1.34 3.76 1.98 5.66v.01c.03.09.06.17.09.26.65 1.96 1.28 3.94 1.89 5.93v.01c.66 2.16 1.29 4.34 1.89 6.53.99 3.6 1.92 7.24 2.75 10.92v.01h.01c.09.39.16.79.25 1.19v.01c.9 4.05 1.7 8.15 2.41 12.29v.01c.66 3.86 1.24 7.76 1.72 11.69v.01l.09.77c.03.26.05.51.08.77v.01l.09.76v.01c.91 7.99 1.46 16.13 1.63 24.4v.03c.04 1.84.07 3.69.07 5.55 0 1.85-.03 3.7-.07 5.54-.42 20.66-3.2 40.51-8.08 59.35-2.6 10.02-5.79 19.75-9.55 29.16-3.39 8.5-7.25 16.73-11.53 24.68-.76 1.42-1.53 2.82-2.32 4.22a238.214 238.214 0 01-21.5 31.82c-11.36 14.19-24.31 27.04-38.62 38.36-2.34 1.85-4.72 3.65-7.14 5.42-4.2 3.08-8.49 6.04-12.9 8.86-3.16 2.02-6.37 3.96-9.63 5.85-17.66 10.2-36.8 18.37-57.11 24.25-2.17.63-4.35 1.23-6.54 1.81-.23.06-.46.11-.69.17-11.91 3.09-24.2 5.4-36.81 6.88-2.48.29-4.98.55-7.48.77-4.94.45-9.93.76-14.97.95l-2.49.09c-2.84.09-5.7.14-8.57.14-1.1 0-2.2-.01-3.3-.02v-.04h-2.27c-.97-.02-1.93-.04-2.89-.07v-.01H247c-.68-.02-1.36-.05-2.04-.07v-.02h-.4l-1.32-.06v-.03h-.68l-1.04-.05v-.03h-.69l-.95-.05v-.04h-.61l-1.11-.06v-.02h-.31c-.79-.05-1.57-.11-2.35-.16v-.02h-.19l-.85-.06v-.02h-.31l-.72-.06v-.03h-.39l-.64-.05v-.03h-.41l-.62-.06v-.03h-.38l-.66-.06v-.03h-.3l-.73-.06v-.02h-.2l-.74-.07v-.02h-.16l-.88-.08-.77-.08v-.01h-.06l-.63-.07v-.02h-.19l-.58-.06v-.02h-.22l-.47-.05v-.04h-.3l-.47-.05v-.03h-.29l-.49-.06v-.03h-.25l-.44-.05v-.04h-.28l-.49-.05v-.03h-.22l-.47-.06v-.03h-.22l-.55-.07v-.01h-.13l-.56-.08v-.01h-.11c-.62-.08-1.25-.16-1.87-.25v-.01h-.06l-.54-.07v-.01h-.08l-.44-.07v-.02h-.17l-.43-.06v-.03h-.17l-.43-.06v-.02h-.16l-.36-.05v-.04h-.22l-.38-.05v-.03h-.2l-.31-.05v-.04h-.26l-.35-.05v-.04h-.21l-.39-.06v-.02h-.16l-.36-.06v-.03h-.18l-.42-.06v-.02h-.12l-.39-.07v-.02h-.14l-.47-.08v-.01h-.06l-2-.33v-.01h-.04l-.39-.07v-.02h-.1l-.42-.07v-.01h-.07l-.36-.06v-.03h-.13l-.3-.05v-.03h-.18l-.33-.06v-.03h-.14l-.29-.05v-.04h-.18l-.34-.06v-.02h-.13l-.3-.06v-.03h-.16l-.27-.05v-.03h-.19l-.32-.07v-.02h-.13l-.3-.06v-.03h-.15l-.28-.05v-.03h-.17l-.35-.07v-.02h-.09l-.34-.07v-.01h-.1l-.42-.09h-.01l-.42-.08v-.01h-.01l-.42-.08h-.01l-.42-.09-.77-.16v-.01h-.07l-.36-.08v-.01h-.05l-.38-.08h-.03l-.31-.07v-.02h-.1l-.33-.07v-.01h-.07l-.28-.06v-.03h-.12l-.31-.07v-.02h-.1l-.24-.05v-.03h-.15l-.28-.06v-.03h-.11l-.24-.05v-.03h-.15l-.28-.07V490h-.11l-.23-.05v-.04h-.16l-.27-.06v-.02h-.11l-.24-.06v-.03h-.14l-.29-.06v-.02h-.08l-.26-.06v-.03h-.12l-.31-.07v-.02h-.06l-.28-.06v-.02h-.09l-.34-.08v-.01h-.03c-.48-.11-.96-.23-1.44-.34h-.01l-.33-.08v-.01h-.02l-.32-.08h-.04l-.31-.08v-.01h-.04l-.3-.07v-.02h-.05l-.3-.07v-.01h-.05l-.29-.07v-.02h-.06l-.28-.07v-.01h-.06l-.29-.08v-.01h-.06l-.2-.05v-.04h-.14l-.2-.05v-.03h-.14l-.2-.06v-.03h-.14l-.21-.05v-.03h-.12l-.22-.06v-.03h-.11l-.24-.06v-.03h-.1l-.24-.06v-.02h-.09l-.25-.07v-.02h-.08l-.27-.07v-.01h-.05l-.29-.08v-.01h-.04l-.22-.06v-.03h-.1l-.24-.06v-.02h-.08l-.27-.07v-.02h-.06l-.28-.08h-.04l-.31-.09-.62-.17c-4.55-1.21-9.41-.89-13.7 1.02l-16.98 7.49-33.83 14.91c-.25.11-.5.22-.75.32-.23.09-.46.17-.68.25h-.01v.01l-.24.08h-.02v.01c-.07.02-.15.05-.23.08h-.03c-.08.03-.16.06-.24.08h-.01v.01c-.65.2-1.31.37-1.96.51h-.02c-.14.03-.28.06-.42.08h-.01v.01c-.35.06-.7.12-1.05.17h-.07c-1.74.23-3.47.22-5.16 0z"/><path fill="#fff" fill-rule="nonzero" d="M102.27 320.93l75.2-119.29c11.97-19.01 37.57-23.68 55.55-10.24l59.84 44.86c5.51 4.1 13.06 4.1 18.5-.06l80.77-61.32c10.75-8.19 24.83 4.74 17.66 16.2l-75.26 119.23c-11.97 19.01-37.57 23.68-55.55 10.24l-59.84-44.87c-5.51-4.09-13.06-4.09-18.5.07l-80.77 61.31c-10.75 8.19-24.83-4.67-17.6-16.13z"/></svg>    </a>

    <!-- The Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Chat</h5>
                    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="chat-box" style="height: 600px;  overflow-y: auto; padding: 10px;">
                        <div v-for="msg in messages" :key="msg.id" class="mb-2">

                            <div class="d-flex justify-content-start mb-2" v-if="msg.sender && msg.sender.id !== seeker.id">
                                <div class="mr-2">
                                    <img style="width: 40px; height: 40px; border: 1px solid; border-radius: 50px" src="https://www.w3schools.com/howto/img_avatar.png" alt="image">
                                </div>
                                <div class="p-2 bg-light rounded">
                                    <strong>{{ msg.sender ? msg.sender.name : 'Unknown' }}:</strong>
                                    <span>{{ msg.message_content }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-2" v-else>
                                <div class="mr-2">
                                    <img style="width: 30px; height: 30px; border: solid 1px; border-radius: 50px" :src="storageImage(seeker.image)" alt="image"/>

                                </div>
                                <div class="p-2 bg-light rounded position-relative">
                                    <strong>{{ seeker.name }}</strong>
                                    <button
                                            class="delete-btn btn deletebutton btn-sm m-lg-3"
                                            @click="deleteMessage(msg.id)"
                                            title="Delete Message"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="message-content d-flex justify-content-between align-items-center">
                                        <span>{{ msg.message_content }}</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <form @submit.prevent="sendMessage">
                        <div class="input-group mt-3">
                            <input
                                    type="text"
                                    v-model="newMessage"
                                    class="form-control border-secondary"
                                    placeholder="Type your message..."
                                    aria-label="User's message"
                                    required
                            />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>

                    </form>
                </div>


            </div>
        </div>
    </div>

</div>

</template>

<script>
    import axios from 'axios';
    import Toast from "vue-toastification";
    import multiselect from 'vue-multiselect'
    import {EventBus} from "../../eventBus";
    import VueMultiselect from "vue-multiselect/src/Multiselect";

    export default {

        name: "SeekerProfile",
        components: {multiselect},
        data() {
            return {
                seeker: {},
                applications: [],
                savedJobs: [],
                components: {
                    Toast,
                },
                educations: ['Secondary School (SSC)', 'Higher Secondary School (HSC)', 'Diploma in Engineering', 'Bachelor\'s Degree', 'Master\'s Degree', 'Professional Degree', 'Postgraduate Degree', 'Ph.D.'],
                skillname:[ 'JavaScript', 'Python', 'Java', 'C++', 'Laravel','vue', 'PHP', 'C#', 'TypeScript', 'HTML', 'CSS', 'React', 'Angular', 'SQL','MongoDB', 'Express.js', 'Node', 'Bootstrap', 'Django','Git'],

                msg: {
                    replies: []
                },
                messages: [],
                newMessage: '',
                UserId: [],
            }
        },
        computed: {
            parsedEducation() {
                try {
                    return JSON.parse(this.seeker.education);
                } catch (error) {
                    return [];
                }
            } ,
            parsedSkills() {
                try {
                    return JSON.parse(this.seeker.skills);
                } catch (error) {
                    return [];
                }
            }
        },

        mounted() {
            this.seekerdata();
            this.SavedJobs();
            this.getRequiredData(['district']);
            this.fetchMessages();
            // setInterval(() => {
            //     this.fetchMessages();
            // }, 1000);

        },
        methods: {
            goToUpdateProfile() {
                this.$nextTick(() => {
                    this.$router.push({ hash: '#update-profile' }).then(() => {
                        window.location.reload();
                    });
                });
            },

            async fetchMessages() {
                try {
                    const response = await axios.get('/api/frontend/fetchmessage');
                    this.messages = response.data.result;


                } catch (error) {
                    console.error("Error fetching messages:", error);
                }

            },
            async sendMessage() {
                try {
                    const response = await axios.post('/api/seekerstore', {
                        receiver_id: 2,
                        message_content: this.newMessage,
                    });
                    this.newMessage = '';
                    this.fetchMessages();
                } catch (error) {
                    console.error("Error sending message:", error);
                }
            },
            async SavedJobs() {
                try {
                    const response = await axios.get('/api/frontend/saved');
                    this.savedJobs = response.data.result;
                } catch (error) {
                    console.error('Error fetching saved jobs:', error);
                }
            },
            async seekerdata() {
                try {
                    const response = await axios.get('/api/frontend/seekerdata');
                    this.applications = response.data.result.applications;
                    this.seeker = response.data.result.seeker;
                } catch (error) {
                    console.error('Error fetching saved jobs:', error);
                }
            },


            async deleteJob(id) {
                const result = await this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await axios.delete(`/api/frontend/saved/${id}`);
                        this.savedJobs = this.savedJobs.filter(job => job.id !== id);
                        this.$swal.fire('Deleted!', 'Your job has been deleted.', 'success');
                    } catch (error) {
                        console.error('Error deleting job:', error);
                        this.$swal.fire('Error!', 'There was a problem deleting the job.', 'error');
                    }
                }
            },


            async deleteMessage(messageId) {
                const result = await this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.isConfirmed) {
                    try {
                        await axios.delete(`/api/messages/${messageId}`);
                        this.messages = this.messages.filter(msg => msg.id !== messageId);
                        this.$swal.fire('Deleted!', 'Your job has been deleted.', 'success');

                    } catch (error) {
                        console.error("Error deleting message:", error);
                        this.$swal.fire('Deleted!', 'Your job has been deleted.', 'success');

                    }
                }
            }



        }
    }
</script>


<style scoped>
    @import 'vue-multiselect/dist/vue-multiselect.min.css';
    .image_upload img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 4px;
    }

    .image_upload {
        height: 120px;
        width: 30%;
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

    #chatModal .modal-dialog {
        max-width: 700px;
        width: 100%;
    }
    .message-content {

        width: 300px;
        padding: 5px;
        border-radius: 5px;
    }
    .deletebutton{
        background-color: #EFFDF5;
    }

</style>