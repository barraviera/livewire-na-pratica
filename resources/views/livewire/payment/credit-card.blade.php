<div class="max-w-7xl mx-auto py-15 px-4" x-data="creditCard()" x-init="PagSeguroDirectPayment.setSessionId('{{$sessionId}}')">
    <!-- x-init="PagSeguroDirectPayment.setSessionId('{{-- $sessionId --}}')" -->
    <!-- precisamos disso para que possamos buscar a bandeira do cartao, gerar o token do cartao, etc -->

    @include('includes.message')

    <div class="flex flex-wrap -mx-3 mb-6">

        <h2 class="w-full px-3 mb-6 border-b-2 border-cool-gray-800 pb-4">
           Realizar Pagamento Assinatura - {{ $plan->name }}
        </h2>
    </div>

    <form action="" name="creditCard" class="w-full max-w-7xl mx-auto">

        <div class="flex flex-wrap -mx-3 mb-6">

            <p class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Número Cartão</label>
                <input x-on:keyUp="getBrand" type="text" name="card_number" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            </p>

            <p class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb2">Nome Cartão</label>
                <input type="text" name="card_name" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            </p>

        </div>

        <div class="flex -mx-3">

            <p class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb2">Mês Vencimento</label>
                <input type="text" name="card_month" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            </p>

            <p class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb2">Ano Vencimento</label>
                <input type="text" name="card_year" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            </p>

        </div>

        <div class="flex flex-wrap -mx-3 mb-6">

            <p class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb2">Código de Segurança</label>
                <input type="text" name="card_cvv" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            </p>

            <p class="w-full py-4 px-3 mb-6">
                <button x-on:click.prevent="cardToken" type="submit" class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded">Realizar Assinatura</button>
            </p>

        </div>

    </form>

    <!-- Script do pagseguro -->
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

    <script>
        /*
            - ter uma sessao vindo do pagseguro
            - precisar importar o js do pagseguro
            - recupere a bandeira do cartao pela lib
            - recuperar o token do cartao: o token que é enviado, e nao os dados do cartao. A gente obtem este token atraves do js do pagseguro
            - enviar o token e o sender hash(otem pelo js do pgseguro) para o componente php
        */
        function creditCard()
        {
            //criamos uma funcao e retornamos um objeto
            return {
                brandName: '', //criamos um atributo chamado brandName que inicialmente será vazio, sera o nome do cartao

                getBrand(e){ //(e) é o evento em questao que o proprio js injeta pra gente

                    let cardNumber = e.target.value; //o target é o nosso input e o value é o valor digitado nesse input

                    //se o tamanho do numero digitado ja atingiu 6 digitos, iremos executar a ação abaixo getBrand
                    if(cardNumber.length == 6){

                        //este trecho pegamos da documentacao do pagseguro
                        PagSeguroDirectPayment.getBrand({
                            cardBin: cardNumber,

                            success: (response) => {
                               this.brandName = response.brand.name; //temos a bandeira recuperada
                            },

                        });

                    }

                },
                cardToken(e){

                    let button = e.target;
                    button.disabled = true;
                    //adicionamos duas classes do tailwind no botao
                    button.classList.add('cursor-not-allowed', 'disabled:opacity-25');
                    button.textContent = 'Carregando...';

                    //pegamos o form que tem o nome de creditCard
                    let formEl = document.querySelector('form[name=creditCard]');
                    let formData = new FormData(formEl); //agora com isso podemos recuperar as informações digitadas no form e passar para a funcao abaixo

                    PagSeguroDirectPayment.createCardToken({
                        cardNumber: formData.get('card_number'), // Número do cartão de crédito que pegamos do form via js
                        brand: this.brandName, // Bandeira do cartão
                        cvv: formData.get('card_cvv'), // CVV do cartão que pegamos do form via js
                        expirationMonth: formData.get('card_month'), // Mês da expiração do cartão que pegamos do form via js
                        expirationYear: formData.get('card_year'), // Ano da expiração do cartão, é necessário os 4 dígitos que pegamos do form via js
                        success: function(response) {
                            let payload = {

                                'token' : response.card.token,
                                'senderHash' : PagSeguroDirectPayment.getSenderHash(),
                                //obs. nao iremos trafegar os dados do cartao do front para o backend, mas sim, iremos trafegar somente esse token gerado com base
                                //nos dados do cartao

                            };
                            console.log(payload); //vai conter um hash unico para o usuario do momento
                            //vamos pegar esse payload e manda para o php para o php fazer a requisicao na api do pagseguro e solicitar a adesão do plano
                            Livewire.emit('paymentData', payload); //com essa linha conseguimos enviar informações para o php

                            //escutar o evento emitido da CreditCard.php apos realizado a adesao a um plano e depois redirecionamos o usuario para a rota de dashboard
                            Livewire.on('subscriptionFinished', result => {
                                formEl.reset(); //limpar o formulario
                                location.href = '{{route('dashboard')}}'; //redirecionar o usuario
                            });
                        }

                    });
                }
            }
        }
    </script>

</div>
