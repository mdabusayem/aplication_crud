<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Applicant</h4>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" @submit.prevent="create">
                        <div class="row">
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" v-model="applicant.name" class="form-control"  />
                                <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" v-model="applicant.email" class="form-control"  />
                                <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" id="image" @change="handleImageUpload" class="form-control" accept="image/*"  />
                                <span class="text-danger" v-if="errors.image">{{ errors.image[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <div>
                                    <input type="radio" id="male" value="Male" v-model="applicant.gender"  />
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="female" value="Female" v-model="applicant.gender"  />
                                    <label for="female">Female</label>
                                </div>
                                <span class="text-danger" v-if="errors.gender">{{ errors.gender[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12 mb-2">
                            <div class="form-group">
                                <label for="skills">Skills:</label>
                                <div v-for="(skill, index) in skillsList" :key="index">
                                <input
                                    type="checkbox"
                                    :id="'skill_' + index"
                                    :value="skill"
                                    v-model="applicant.skills"
                                >
                                <label :for="'skill_' + index">{{ skill }}</label>
                                </div>
                                <span class="text-danger" v-if="errors.skills">{{ errors.skills[0] }}</span>
                            </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save</button>
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
    name:"add-category",
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
            errors: {},
        }
    },
    methods:{
        async create(){
            //console.log(this.applicant.skills);
            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
               // this.applicant.append('image', this.file);

            await axios.post('/api/applicants',this.applicant,config).then(response=>{
                console.log(response.data);
                 this.$swal('Success', response.data.message, 'success');
                this.$router.push({name:"applicanList"})
            }).catch(error=>{
                this.$swal('Error', 'Error found', 'error');
                if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                console.log(error.response.data.message)
            })
        },
        handleImageUpload(event) {
            this.applicant.image = event.target.files[0];
        },
    }
}
</script>