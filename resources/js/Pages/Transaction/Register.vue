<script setup>
import { vMaska } from "maska"
</script>

<script>
import { Money3Component } from "v-money3";
import { defineComponent } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default defineComponent({
  name: "App",
  components: {
    money3: Money3Component, AuthenticatedLayout, Head, vMaska
  },
  data() {
    return {
      amount: "0.00",
      form: {
        money: 0,
        cpf_cnpj: 0
      },
    };
  },
  computed: {
    config() {
      return {
        decimal: ",",
        thousands: ".",
        prefix: "R$ ",
        suffix: "",
        precision: 2,
        masked: false /* doesn't work with directive */,
      };
    },
  },
  methods: {
    async submit() {
        await axios.post(route('transactions.store'), this.form)
            .then((res) => {
                if ( res.data ) {
                    toast.success("Sucesso ao salvar a transação."); 
                }
            })
            .catch((err) => {
                Object.entries(err.response.data.errors).forEach(element => {
                    toast.error(element[1]); 
                });
            });
        }
    }
})
</script>


<template>
    <Head title="Transferência de Valores" />

    <AuthenticatedLayout>
        <div class="container mx-auto">
            <div class="row mx-auto text-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-5">Transferência de Valores</h2>
            </div>
            <div class="py-12">
                <div class="mb-6">
                    <label for="cpf_cnpj" class="block mb-2 text-sm font-medium text-gray-900">CPF/CNPJ</label>
                    <input type="text" v-maska data-maska="['###.###.###-##','##.###.###/####-##']" v-model="form.cpf_cnpj" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                </div>
                <div class="mb-6">
                    <label for="value" class="block mb-2 text-sm font-medium text-gray-900">Valor</label>
                    <money3 v-model="form.money" v-bind="config" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                </div>
                <a type="button" href='/transactions/' class="float-left bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
                <button type="button" @click="submit" class="float-right bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Enviar
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
