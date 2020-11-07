 <template>
    <div>
        <button class="ml-4 btn btn-primary" @click="followUser" v-text="buttonText"></button>
    </div>
</template>

<script>
    export default {
        props: ['userId','follows'],

        mounted() {
            console.log('Component mounted.')
        },

        // if a user is attached/following to the profile 
        // we are getting this by status
        data: function(){
            return{
                status:this.follows
            }
        },

        methods:{
            followUser(){
                axios.post('/follow/'+this.userId)
                .then(response=>{
                    this.status = ! this.status

                    console.log(response.data);
                    }
                )   

                .catch(errors=>{
                    if(errors.response.status == 401)
                    {
                        window.location= '/login';
                    }
                })
            }
        },
            // communicating with the Route in web.php
            // it is matching the " '/follow/'+this.userId " string in a post Route 
            // the response is the returned data from the Store method in FollowsController

        computed:{
            buttonText(){
                return (this.status) ? 'Unfollow' : 'Follow';
            }
        }
    }
</script>
