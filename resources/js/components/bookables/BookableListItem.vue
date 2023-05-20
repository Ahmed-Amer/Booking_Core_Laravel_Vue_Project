<template>
    <div class="card w-100">
        <div class="card-body">
            <router-link :to="{name : 'showbookable' , params : { id : bookable.id}}">
                <h5 class="card-title">{{bookable.title}}</h5>
            </router-link>
            <div class="card-text">{{bookable.description}}</div>
        </div>
    </div>
</template>

<script>
export default
{
    props: ['bookable']
}
</script>
<style scoped>
.card-title{
    color: #63adef;
}
</style>