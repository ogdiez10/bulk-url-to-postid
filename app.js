Vue.component('vue-plugin', {
    data(){
        return{
            searching: '',
            posts: '',
            allitems: [],
            lines: '',
        };
    },
    methods: {
        async fetchIDs(){

            var bulk = this.searching;
            this.lines = bulk.split(/\r?\n/);
            this.lines.forEach((element) => {
            var url = wnm_custom.blog_url + '/wp-json/bulk-url-to-postid/v1/url/' + element;
            fetch(url)
            .then((res) => {
                return res.text();
            })
            .then ((res) => {
                this.posts = JSON.parse(res);
                if(this.posts.id){  this.allitems.push([element, this.posts.id]); }
                else {  this.allitems.push([element, 'NOT FOUND']); } 
               
            });
            
            
            });

            

            
        },
    },
    template: `<div>
    <h2>Get Wordpress Post ID from a list of URL's</h2>
    <p>Paste your URLs on the box and push the button (each URL in a new line, without commas or other characters).</p>
    <textarea v-model="searching" placeholder="https://mydomain.com/category/post-slug
https://mydomain.com/category/post-slug2
https://mydomain.com/category/post-slug2" />
<button @click="fetchIDs();">Check IDs</button>
<h3>Results: </h3>
<table cellspacing="0" border="1">
      <tr>
      <th>URL</th>
      <th>Found ID</th>
      </tr>
      <tr v-for="(row, index_row) in allitems" :key="row.nr">
        <td
          v-for="(cell, index_subcol) in row"
          :key="index_subcol"
          :id="index_row + '-' + index_subcol"
        >
          {{ cell }}
        </td>
      </tr>
    </table>
    </div>`,
});

var vm = new Vue({ el: '#bulkUrlIdApp' });