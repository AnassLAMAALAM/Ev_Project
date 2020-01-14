<template>
  <b-container fluid>
    <!-- User Interface controls -->
    <b-row>
      <b-col lg="6" class="my-1">
        <b-form-group
          label="Sort"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="sortBySelect"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
              <template v-slot:first>
                <option value="">-- none --</option>
              </template>
            </b-form-select>
           
          </b-input-group>
        </b-form-group>
      </b-col >

      <b-col lg="3" class="my-1" > </b-col>

      <b-col lg="3" class="my-1" >
        <router-link to="/new-aid-visual" >  
        <a class=" btn btn-outline-primary" v-b-tooltip title="new aid visual" to="/new-aid-visual" role="button"><i class="fas fa-plus"> Add new aid visual</i></a>
        </router-link>
        

        
      </b-col>
      <b-col lg="6" class="my-1">
        <b-form-group
          label="Filter"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="filterInput"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="filter"
              type="search"
              id="filterInput"
              placeholder="Type to Search"
            ></b-form-input>
            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
     
      </b-col>

      <b-col sm="5" md="6" class="my-1">
        <b-form-group
          label="Per page"
          label-cols-sm="6"
          label-cols-md="4"
          label-cols-lg="3"
          label-align-sm="right"
          label-size="sm"
          label-for="perPageSelect"
          class="mb-0"
        >
          <b-form-select
            v-model="perPage"
            id="perPageSelect"
            size="sm"
            :options="pageOptions"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>

    <!-- Main table element -->
    <b-table
      show-empty
      small
      stacked="md"
      :items="Evs"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :filterIncludedFields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      @filtered="onFiltered"
    >
      <template v-slot:cell(image)="row">
        <img  :src="row.item.image" style="width:10%" />
      </template>

       <template v-slot:cell(pdf)="row">
        <a :href="row.item.pdf"> {{row.item.title}} </a>
      </template>

      <template v-slot:cell(actions)="row">
        
       <button  type="button" class="btn btn-outline-danger" v-b-tooltip title="delete" @click="removeEv(row.item.id)"> <i class="far fa-trash-alt"></i></button>
        <a class="btn btn-outline-secondary" :href="`${row.item.pdf}`" v-b-tooltip title="show pdf" role="button"><i class="fas fa-file-pdf"></i></a>
        <router-link to="/new-aid-visual">
            <a class="btn btn-outline-primary" v-b-tooltip title="new aid visual" to="/new-aid-visual" role="button"><i class="fas fa-plus"></i></a>
        </router-link>
        <button class="btn btn-outline-success" v-b-tooltip title="edit"  @click="edit(row.item.id)" role="button"><i class="fas fa-edit"></i></button>
        
      </template>

      <template v-slot:row-details="row">
        <b-card>
          <ul>
            <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
          </ul>
        </b-card>
      </template>
    </b-table>

    <!-- Info modal -->
    <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
      <pre>{{ infoModal.content }}</pre>
    </b-modal>
  </b-container>
</template>

<script>
  export default {
    data() {
      return {
          
        /*Ev: [
          { isActive: true, age: 40, name: { first: 'Dickerson', last: 'Macdonald' } },
          { isActive: false, age: 21, name: { first: 'Larsen', last: 'Shaw' } },
          { isActive: false, age: 89, name: { first: 'Geneva', last: 'Wilson' } },
          { isActive: true, age: 38, name: { first: 'Jami', last: 'Carney' } },
          { isActive: false, age: 27, name: { first: 'Essie', last: 'Dunlap' } },
          { isActive: true, age: 40, name: { first: 'Thor', last: 'Macdonald' } },
          { isActive: false, age: 26, name: { first: 'Mitzi', last: 'Navarro' } },
          { isActive: false, age: 22, name: { first: 'Genevieve', last: 'Wilson' } },
          { isActive: true, age: 38, name: { first: 'John', last: 'Carney' } },
          { isActive: false, age: 29, name: { first: 'Dick', last: 'Dunlap' } }
        ]*/
        Evs:[],
        types:[],
        fields: [
          
          { key: 'title', label: 'Title', sortable: true, sortDirection: 'desc' },
          { key: 'image', label: 'Image', sortable: true, sortDirection: 'desc' },
          { key: 'pdf', label: 'PDF', sortable: true, sortDirection: 'desc' },
          { key: 'type.type', label: 'Type', sortable: true, sortDirection: 'desc' },
          { key: 'actions', label: 'Actions' }
        ],
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15],
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: ' ',
        filterOn: [],
        infoModal: {
          id: 'info-modal',
          title: '',
          content: ''
        }
      }
    },
    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }      
    },
    created(){
        this.freshEvs();
    },
    mounted() {
      // Set the initial number of Ev
      this.totalRows = this.Evs.length
    },
    methods: {
      
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
      freshEvs() {
          window.axios.get('/evs').then(({
              data
          }) => {
               this.Evs = data;
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
                        this.freshEvs();
                        this.$toast.success(data.message, "Success", { timeout: 2000 });
                    });
                    setTimeout(() => {
                    
                    }, 3000);
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                }, true],
                ['<button>NO</button>', function (instance, toast) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                }],
            ]            
            }); 
            },
      info(item, index, button) {
        this.infoModal.title = `Row index: ${index}`
        this.infoModal.content = JSON.stringify(item, null, 2)
        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },
      resetInfoModal() {
        this.infoModal.title = ''
        this.infoModal.content = ''
      },
      onFiltered(filteredEvs) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredEvs.length
        this.currentPage = 1
      }
    }
  }
</script>

<style >

table tr th:nth-child(5){
  width:20%;
}

</style>