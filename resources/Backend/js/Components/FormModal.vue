<template>
    <div class="modal fade" :id="modalId" >
        <div class="modal-dialog modal-lg">
            <form @submit.prevent="submit()">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{$route.meta.pagetitle}}
                        </h5>
                        <button
                                type="button"
                                class="btn-close"
                                @click="closeModal(modalId)"
                                aria-label="Close"
                        ></button>
                    </div>


                    <div class="modal-body" >
                        <slot></slot>
                    </div>


                    <div class="modal-footer">
                        <button
                                type="button"
                                class="btn btn-secondary"
                                @click="closeModal(modalId)">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "formModal",
        props: {
            modalId: {
                type: [String],
                default: "myModal",
            },
        },
        methods: {
            submit: function () {
                this.$emit("submit");
            },
        },
    };
</script>

<style scoped>

</style>


