<template>

<div>


  <b-form @submit="formSubmit" >
  <b-card bg-variant="light">

    <b-form-group
      label-cols-lg="3"
      label="Update Visuel Aid"
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
        <b-form-input v-model="ev.title" :class="[errors.title ? 'form-control is-invalid' : 'form-control']" id="nested-title"></b-form-input>
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
        <img style="width:100px;" :src="showImage" v-if="showImage != '/'" />
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
        <b-form-select v-model="selectedEv" @change="onChange"  :class="[errors.type ? 'form-control is-invalid' : 'form-control']" id="nested-type">
            <option v-for="(type) in types" :key="type.id" > {{ type.type }}  </option>
        </b-form-select >
          <span v-if="errors.type" style="color:red;" >
               {{ errors.type[0] }} {{ selectedEv }}
          </span>

        <div style="margin-top:10px;">
            <b-button block variant="primary" type="submit" >
              <i class="far fa-save"></i> Save
             </b-button>
        </div>
      
      </b-form-group>



    </b-form-group>
  </b-card>

  </b-form>
</div>
</template>


<script>
import { log } from 'util';
    export default {
        name:'editAidVisual',
        data() {
            return {
                id : '',
                selectedEv : '',
                selectedIndex : -1,
                ev : {},
                types:[],
                errors : [],
                title:'',
                title_error : '',
                image_error : '',
                type_error : '',
                type:'',
                image:'',
                pdf:'',
                imageChange : ''
            }
        },
        computed: {
          // un accesseur (getter) calculé
          showImage: function () {
            if(!this.imageChange)  return this.ev.image;
            else {
               const file = this.ev.image;
               const url = URL.createObjectURL(file);
                return url;
            }
          }},
          mounted: function () {
            
            
            this.id = this.$route.query.id;
             window.axios.get('/type/edit/'+ this.id).then(({ data }) => {
             this.ev = data;   
             console.log(this.ev.type.type);
             //alert(this.ev.id);

             this.selectedEv = this.ev.type.type;
             this.freshTypes();
                       
            });
            },
              methods:{
                  freshTypes() {
                    window.axios.get('/types').then(({
                        data
                    }) => {
                        this.types = data;
                        
                    });
                },
      
                onChange(){

                for (let index = 0; index < this.types.length; index++) {
                  if (this.types[index].type == this.selectedEv) {
                    this.selectedIndex = index;
                    
                  }
                  
                }

             
                   
                  
                },

             onImageChange(event){
              this.imageChange = true;
              this.ev.image = event.target.files[0];
              
        },
        onPDFChange(event){
              this.ev.pdf = event.target.files[0];
        },


        formSubmit (event) {
        event.preventDefault();
        let currentObj = this;
        
         

        const config = {
            headers: { 'content-type': 'multipart/form-data' }
        }
        let formData = new FormData();

        formData.append('title', this.ev.title);

        if(this.selectedIndex == -1){
          formData.append('type', this.ev.type.id);
        }else{
          formData.append('type', this.types[this.selectedIndex].id);
        }
        formData.append('image', this.ev.image);
        formData.append('pdf', this.ev.pdf);
        axios.post(`/ev/update/${this.ev.id}`,formData, config)
          .then(res => {
            console.log(res.data.message);
            this.$toast.success(res.data.message, "Success", { timeout: 3000});
            this.ev.title = '';
            this.ev.type = '';
            this.ev.image = '';
            this.ev.errors = [];

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