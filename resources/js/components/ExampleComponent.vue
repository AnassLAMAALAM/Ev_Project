<template>
    <div>
        <!-- Tabs with card integration -->
        <b-card no-body >
            <b-tabs v-model="tabIndex" card align="center">
                <b-tab :title="(type.type +' ('+type.evs_count+')')"  :key="type.id" v-for="type in types">
                    <div>
                        <b-container class="bv-example-row">
                         

                            <b-card-group deck>
                             
                            <div class="row">
                              <div class="col col-lg-4" :key="ev.id" v-for="ev in type.evs" style="margin-top:20px;">

                            <b-card 
                              border-variant="primary"
                              :header="ev.title"
                              header-bg-variant="primary"
                              header-text-variant="white"
                              align="center" 
                            >

                              <b-img @click="modalShowMethod(ev.image)" center style="width:100%;height:180px;cursor:pointer"  :src="ev.image" alt="Center image">x²</b-img>
                                  <b-modal size="xl" v-model="modalShow">

                                    <b-img  center    :src="image" alt="Center image"> </b-img>
                                  </b-modal>

                            </b-card>
                                 </div>
                                </div>
                            </b-card-group>
                        </b-container>

                    </div>

                </b-tab>

                 <template v-slot:tabs-end>
                    <b-nav-item v-show="isadmin" v-b-modal.modalPopover  href="#"><b>+</b></b-nav-item>
                     <b-modal id="modalPopover" title="Add a new Type"
                     @ok="handleOk" >
                        <form ref="form" @submit.stop.prevent="handleSubmit">

                    <b-form-group
                    label="New Type"
                    label-for="type-input"
                    invalid-feedbackçç="Name is required"
                    >
                    <b-form-input
                        id="type-input"
                        v-model="newType"
                        required
                    ></b-form-input>
                    </b-form-group>
                    </form>
                </b-modal>


                </template>

            </b-tabs>
        </b-card>

        <!-- Control buttons-->
        <div class="text-center">
            <b-button-group class="mt-2">
                <b-button @click="tabIndex--">Previous</b-button>
                <b-button @click="tabIndex++">Next</b-button>
            </b-button-group>

            <div class="text-muted">Current Tab: {{ tabIndex }}</div>
        </div>
    </div>
</template>

<script>
    export default {
      props:['isadmin'],
        data() {
            return {
                tabIndex: 0,
                modalShow: false,
                image : '',
                types : [],
                newType : '',
                variants: ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'light', 'dark'],
                headerBgVariant: 'dark',
                headerTextVariant: 'light',
                bodyBgVariant: 'light',
                bodyTextVariant: 'dark',
                footerBgVariant: 'warning',
                footerTextVariant: 'dark'
            }
        },
          created: function () {
             console.log(this.isadmin);
              this.freshTypes();
            },
              methods:{
                  freshTypes(){
                    window.axios.get('/types').then(({ data }) => {
                    this.types = data;             
                    });
                  },
              modalShowMethod : function (image) {
                this.modalShow = !this.modalShow;
                this.image = image;
              },

              handleOk(bvModalEvt) {
                    // Prevent modal from closing
                    bvModalEvt.preventDefault()
                    // Trigger submit handler
                    this.handleSubmit()
                },

              handleSubmit:function () {

                    let currentObj = this;
        let formData = new FormData();
        formData.append('type', this.newType);
         axios.post('/type/create',formData)
          .then(res => {
            console.log(res.data.message);
            this.$toast.success(res.data.message, "Success", { timeout: 3000});
            this.newType = '';
            this.freshTypes();

          })
          .catch( error => {
            })





            
            }
            }
    }
</script>