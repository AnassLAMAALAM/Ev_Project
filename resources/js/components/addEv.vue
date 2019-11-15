<template>
<div>
  <b-form @submit="formSubmit" >
  <b-card bg-variant="light">
    <b-form-group
      label-cols-lg="3"
      label="Add New EV"
      label-size="lg"
      label-class="font-weight-bold pt-0"
      class="mb-0"
      @submit="formSubmit" enctype="multipart/form-data"
    >
      <b-form-group
        label-cols-sm="3"
        label="Title:"
        label-align-sm="right"
        label-for="nested-title"
      >
        <b-form-input v-model="title" :class="[errors.title ? 'form-control is-invalid' : 'form-control']" id="nested-title"></b-form-input>
        <span v-if="errors.title" style="color:red;" >
            {{ errors.title[0] }} 
        </span>


      </b-form-group>


       <b-form-group
        label-cols-sm="3"
        label="Image:"
        label-align-sm="right"
        label-for="nested-image"
      >
          <input type="file"  :class="[errors.image ? 'form-control is-invalid' : 'form-control']" @change="onImageChange" multiple  class="form-control">
          <span v-if="errors.image" style="color:red;" >
              {{ errors.image[0] }} 
          </span>
      </b-form-group>

      <b-form-group
        label-cols-sm="3"
        label="File PDF:"
        label-align-sm="right"
        label-for="nested-image"
      >
          <input type="file" :class="[errors.pdf ? 'form-control is-invalid' : 'form-control']"    @change="onPDFChange" multiple  class="form-control">
          <span v-if="errors.pdf" style="color:red;" >
              {{ errors.pdf[0] }} 
          </span>
      </b-form-group>


      
      <b-form-group
        label-cols-sm="3"
        label="Type:"
        label-align-sm="right"
        label-for="nested-type"
      >
        <b-form-select v-model="type" :class="[errors.type ? 'form-control is-invalid' : 'form-control']" id="nested-type">
            <option v-for="type in types"   v-bind:value="type.id" :key="type.id" > {{ type.type }}  </option>
        </b-form-select>
          <span v-if="errors.type" style="color:red;" >
               {{ errors.type[0] }} 
          </span>

        <div style="margin-top:10px;">
            <b-button block variant="primary" type="submit" >
              <i class="fas fa-plus-circle"></i> Add
             </b-button>
        </div>
      
      </b-form-group>



    </b-form-group>
  </b-card>

  </b-form>
</div>
</template>


<script>
    export default {
        data() {
            return {
                types:[],
                errors : [],
                title:'',
                title_error : '',
                image_error : '',
                type_error : '',
                type:'',
                image:'',
                pdf:''
            }
        },
          created: function () {

            window.axios.get('/types').then(({ data }) => {
             this.types = data;             
            });
               
            },
              methods:{

             onImageChange(event){
               alert("image");
              this.image = event.target.files[0];
        },
        onPDFChange(event){
              this.pdf = event.target.files[0];
        },


        formSubmit (event) {
        event.preventDefault();
        let currentObj = this;
        
        const config = {
            headers: { 'content-type': 'multipart/form-data' }
        }
        let formData = new FormData();

        formData.append('title', this.title);
        formData.append('type', this.type);
        formData.append('image', this.image);
        formData.append('pdf', this.pdf);

        console.log(formData);
         axios.post('/ev/create',formData, config)
          .then(res => {
            console.log(res.data.message);
            this.$toast.success(res.data.message, "Success", { timeout: 3000});
            this.title = '';
            this.type = '';
            this.image = '';
            this.errors = [];

          })
          .catch( error => {
               if (error.response.status == 422) {
                 this.errors = error.response.data.errors;
                 console.log(this.errors);
               }
            })
      }



            }
    }
</script>