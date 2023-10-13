<template>
    <div class="row">
        <div class="col-12 mb-2 text-end">
            <router-link :to='{name:"applicantAdd"}' class="btn btn-primary">Create</router-link>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Applicant List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Skills</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="applicants.length > 0">
                                <tr v-for="(applicant,key) in applicants" :key="key">
                                    <td>{{key+1}}</td>
                                    <td>{{ applicant.name }}</td>
                                    <td>{{ applicant.email }}</td>
                                    <td>{{ applicant.gender }}</td>
                                    <td>{{ applicant.skills.toString() }}</td>
                                    <td><img :src="'/storage/uploads/'+applicant.image" width="70" style="height:70px;" alt="No Image" class="img-thumbnail" v-if="applicant.image"></td>
                                    <td>
                                        <router-link :to='{name:"applicantEdit",params:{id:applicant.id}}' class="btn btn-success">Edit</router-link>
                                        <button type="button" @click="deleteapplicant(applicant.id)" class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7" align="center">No applicants Found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"applicants",
    data(){
        return {
            applicants:[]
        }
    },
    mounted(){
        this.getapplicants()
    },
    methods:{
        async getapplicants(){
            await axios.get('/api/applicants').then(response=>{
                this.applicants = response.data.applicants;
                //console.log(this.applicants);
            }).catch(error=>{
                console.log(error)
                this.applicants = []
            })
        },
        confirmDelete() {
            this.$swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this task!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            })
            .then((result) => {
                if (result.value) {
                    // User confirmed the delete action
                    this.deleteTask(); // Call the deleteTask method to perform the actual deletion
                }
            });
        },
        deleteapplicant(id){
            this.$swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this applicant data!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            })
            .then((result) => {
                if (result.value) {
                    axios.delete(`/api/applicants/${id}`).then(response=>{
                    this.getapplicants()
                }).catch(error=>{
                    console.log(error)
                })
                }
            });
           
        },
        
    }
}
</script>