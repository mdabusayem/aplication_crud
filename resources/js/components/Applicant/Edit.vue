<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Update Applicant</h4>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" @submit.prevent="update">
                        <div class="row">
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" v-model="applicant.name" class="form-control" />
                                <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" v-model="applicant.email" class="form-control" />
                                <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                            </div>
                            </div>
                            
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <div>
                                    <input type="radio" id="male" value="Male" v-model="applicant.gender" />
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="female" value="Female" v-model="applicant.gender" />
                                    <label for="female">Female</label>
                                </div>
                                <span class="text-danger" v-if="errors.gender">{{ errors.gender[0] }}</span>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" id="image" @change="handleImageUpload"  class="form-control" />
                                <div v-if="isNull">
                                    <img :src="getPhoto()" width="150" style="height:150px;" alt="Applicant Image" class="img-thumbnail" v-if="applicant.image">
                                </div>
                                <span class="text-danger" v-if="errors.image">{{ errors.image[0] }}</span>
                            </div>
                            </div>
                            </div>
                            
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="skills">Skills:</label>
                                <div v-for="(skill, index) in skillsList" :key="index">
                                    <label>
                                        <input type="checkbox" :value="skill" v-model="applicant.skills"> {{ skill }}
                                    </label>
                                </div>
                                <span class="text-danger" v-if="errors.skills">{{ errors.skills[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"update-applicant",
    data(){
        return {
            skillsList: ['Laravel', 'Vue.js', 'CodeIgniter', 'API', 'Mysql'],
            applicant:{
                name: '',
                email: '',
                image: null,
                gender: '',
                skills: [],
            },
            imageUrl: null,
            isNull:1,
            errors: {},
        }
    },
    mounted(){
        this.showapplicant()
    },
    methods:{
        async showapplicant(){
            await axios.get('/api/applicants/'+this.$route.params.id).then(response=>{
                console.log(response.data.applicant.skills);
               // const { title, description } = response.data.applicant
                this.applicant.name=response.data.applicant.name;
                this.applicant.email=response.data.applicant.email;
                this.applicant.skills=response.data.applicant.skills;
                this.applicant.gender=response.data.applicant.gender;
                this.applicant.image=response.data.applicant.image;
                this.imageUrl=response.data.applicant.image;
            }).catch(error=>{
                console.log(error)
            })
        },
        async update(){
            const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                    }
                }
            await axios.post(`/api/applicants/${this.$route.params.id}`,this.applicant,config).then(response=>{
                this.$swal('Success', response.data.message, 'success');
                this.$router.push({name:"applicanList"})
            }).catch(error=>{
                this.$swal('Error', 'Error found', 'error');
                if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
            })
        },
        handleImageUpload(e) { 
            const file = e.target.files[0];
            this.applicant.image = file;
            this.isNull=0;
        },
        getPhoto(){
            
            let photo ="";
            if(this.applicant.image)
            {
                photo = (this.applicant.image.length > 100) ? this.applicant.image : "/storage/uploads/"+ this.applicant.image;
            }
            else{
                photo = "/storage/uploads/"+ this.applicant.image;
            }
            return photo;
        },
    }
}
</script>
 