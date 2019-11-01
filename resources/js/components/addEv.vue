<template>
<div>
  <b-form @submit="formSubmit" >
  <b-card bg-variant="light">
    <b-form-group
      label-cols-lg="3"
      label="Shipping Address"
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
        <b-form-input v-model="title" id="nested-title"></b-form-input>
      </b-form-group>


       <b-form-group
        label-cols-sm="3"
        label="Image:"
        label-align-sm="right"
        label-for="nested-image"
      >
          <input type="file" @change="onImageChange" multiple  class="form-control">
      </b-form-group>

    
      <b-form-group
        label-cols-sm="3"
        label="Type:"
        label-align-sm="right"
        label-for="nested-type"
      >
        <b-form-select v-model="type"  id="nested-type">
            <option v-for="type in types"  v-bind:value="type.id" :key="type.id" > {{ type.type }}  </option>
        </b-form-select>

        <div style="margin-top:10px;">
            <b-button block variant="primary" type="submit" >Block Level Button</b-button>
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
                title:'',
                type:'',
                image:''
            }
        },
          created: function () {

            window.axios.get('/types').then(({ data }) => {
             this.types = data;             
            });
               
            },
              methods:{

             onImageChange(event){
        this.image = event.target.files[0];
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

        console.log(formData);
         axios.post('/ev/create',formData, config)
          .then(res => {
            console.log(res.data.message);
            this.$toast.success(res.data.message, "Success", { timeout: 3000});
            this.title = '';
            this.type = '';
            this.image = '';

          })
          .catch( error => {
            })
      }



            }
    }
</script>