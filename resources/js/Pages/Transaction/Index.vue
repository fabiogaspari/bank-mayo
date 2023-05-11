<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const data = ref([])

onMounted( () => {
    call();
})

async function call() {
    await axios.get(route("transactions.list.all.by.user.from")) 
        .then((res) => {
            data.value = res.data
        })
        .catch((err) => {
            console.log(err)
        })
}   
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="container mx-auto text-center">
            <div class="row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-5">Extrato</h2>
            </div>
            <div class="py-12">
                <ul class="text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                    <li class="text-left px-4 w-full py-2 border-b border-gray-200 rounded-t-lg" v-for="row in data" :key="row.id">
                        <div class="flex align-center">
                            <div class="w-full pr-4">
                                Data: {{ new Date(row.created_at).toLocaleDateString() }}<br>
                                Valor: {{ new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2, maximumFractionDigits: 3 }).format(row.value) }}
                            </div>
                        </div>
                    </li>
                </ul>
                <a href='/transactions/register' type="button" @click="submit" class="mt-6 float-right bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Novo
                </a>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
