<template>
    <div>

         
        <!-- Tabs with card integration -->
        <b-form-input style="width:40%;margin:10px auto;text-align:center"
          id="input-1"
          type="email" v-model="word_search"
          placeholder="Enter a aid visual title" @keyup="liveSearch" @keyup.backspace="liveSearch"
        ></b-form-input>



        <b-card no-body>
            <div class="text-center" v-if="this.spinning" >
                <b-spinner variant="primary" label="Spinning"></b-spinner>
            </div>
            <b-tabs v-model="tabIndex" card align="center">
                <b-tab :title="(type.type +' ('+type.evs_count+')')" :key="type.id" v-for="type in types">
                    <div>
                        
                        <b-container class="bv-example-row">

                            <b-card-group deck>
                                
                                <div class="row" v-if="type.evs_count>0" style="width: 100%;">
                                    <div class = "col col-lg-4"  :key="ev.id" v-for="(ev,index) in type.evs" style="margin-top:20px;height:320px;">

                                        <b-card border-variant="primary" style="margin-top:20px;height:320px;" :header="ev.title" header-bg-variant="primary" header-text-variant="white" align="center">

                                            <b-img @click="modalShowMethod(ev.image)" center style="width:100%;height:180px;cursor:pointer" :src="ev.image" alt="Center image">x²</b-img>
                                            <b-modal size="xl" v-model="modalShow">

                                                <b-img center style="width:100%;" :src="image" alt="Center image"> </b-img>
                                            </b-modal>
                                            <div style="margin-top:5px;">
                                                <a class="btn btn-outline-secondary" target="_blank" v-b-tooltip title="Show pdf" :href="`${ev.pdf}`" role="button"><i class="fas fa-file-pdf"></i> Show pdf </a>                                          
                                            </div>
                                        </b-card>
                                    </div>
                                </div>

                                <div style="  margin: 0 auto;" v-else>
                                   <h4 style=" font-style: italic;"> {{ type.type }} Not Have any Aid visual  </h4>
                                </div>
                            </b-card-group>
                        </b-container>

                    </div>

                </b-tab>

                <template v-slot:tabs-end>
                    <b-nav-item  v-b-modal.modalPopover href="#"><b>+</b></b-nav-item>
                    <b-modal id="modalPopover" title="Add a new Type" @ok="handleOk">
                        <form ref="form" @submit.stop.prevent="handleSubmit">

                            <b-form-group label="New Type" label-for="type-input" invalid-feedbackçç="Name is required">
                                <b-form-input id="type-input" v-model="newType" required></b-form-input>
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
import { log } from 'util';
    export default {
        name:'home',
        props: ['isadmin'],
        data() {
            return {
                spinning : true,
                word_search : '',
                tabIndex: 0,
                modalShow: false,
                image: '',
                types: [],
                newType: '',
                variants: ['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'light', 'dark'],
                headerBgVariant: 'dark',
                headerTextVariant: 'light',
                bodyBgVariant: 'light',
                bodyTextVariant: 'dark',
                footerBgVariant: 'warning',
                footerTextVariant: 'dark'
            }
        },
        mounted: function() {
            this.$route.query.id = '';
            console.log(this.isadmin);
            this.freshTypes();
        },
        methods: {
         
          liveSearch(){
              this.filteredTypes = [];
              this.evs = [];
              let index = 0;
              let origineTypes = this.types;
            if (this.word_search != "") {
                
            
              for (let i = 0; i < this.types.length; i++) {
         
                 
                  for (let j = 0; j < this.types[i].evs.length; j++) {
                      if (this.word_search != "" && this.types[i].evs[j].title.toLowerCase().includes(this.word_search.toLowerCase())) {
                          console.log(this.types[i].evs[j].title);
                          this.evs.push(this.types[i].evs[j]);
                          index++;
                      }
                      
                  }
                  
              }
                this.filteredTypes.push({ 'type' : 'result','evs':this.evs,'evs_count' : index});
                this.types = this.filteredTypes;
              }

              else{
                   //this.types = origineTypes;
                   this.freshTypes();
              }
         
          },


            edit(id) {
                this.$router.push({ name : "edit-aid-visual" ,query :{ id: id} });
            },
            freshTypes() {
                    this.spinning = true;
                    window.axios.get('/types').then(({
                        data
                    }) => {
                        
                        this.types = data;
                        this.spinning = false;
                    });
                },
                removeEv(evId) {
                    let evs = [];
                    this.types.forEach((value, index) => {
                        evs.push(...value.evs);
                    });

                    this.$toast.question('Are you sure about that?', "Confirm", {
            timeout: 20000,
            close: false,
            overlay: true,
            displayMode: 'once',
            id: 'question',
            zindex: 999,
            title: 'Hey',            
            position: 'center',
            buttons: [
                ['<button><b>YES</b></button>', (instance, toast) => {
                    
                    window.axios.delete(`/evs/${evId}`)
                    .then(({data}) => {
                        this.freshTypes();
                        this.$toast.success(data.message, "Success", { timeout: 2000 });
                    });
                    setTimeout(() => {
                    //this.freshTypes();

                    }, 3000);
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                }, true],
                ['<button>NO</button>', function (instance, toast) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                }],
            ]            
            });            


                       


                    

                },
                modalShowMethod: function(image) {
                    this.modalShow = !this.modalShow;
                    this.image = image;
                },

                handleOk(bvModalEvt) {
                    // Prevent modal from closing
                    bvModalEvt.preventDefault()
                        // Trigger submit handler
                    this.handleSubmit()
                },

                handleSubmit: function() {

                    let currentObj = this;
                    let formData = new FormData();
                    formData.append('type', this.newType);
                    axios.post('/type/create', formData)
                        .then(res => {
                            console.log(res.data.message);
                            this.$toast.success(res.data.message, "Success", {
                                timeout: 3000
                            });
                            this.newType = '';
                            this.freshTypes();

                        })
                        .catch(error => {})

                }
        }
    }
</script>