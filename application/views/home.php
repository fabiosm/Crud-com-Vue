<!DOCTYPE html>
	<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.min.css">  

		<title>Teste BRQ - CRUD</title>
	</head>
	<body>
		<div id="crud_user">
			<v-app>
				<v-main>  
					<v-card class="mx-auto mt-5" max-width="1200">
						<br/>
						<!-- Botão de criar -->
						<v-btn rounded color="primary" @click="model_criar();">
							<v-icon dark>
								mdi-plus
							</v-icon>
							Cadastrar novo
						</v-btn>    

						<!-- Tabela -->
						<v-simple-table class="mt-5">
							<template v-slot:default>
								<thead>
									<tr class="light-blue darken-2">										
										<th class="white--text">NOME</th>
										<th class="white--text">CPF</th>
										<th class="white--text">E-MAIL</th>
										<th class="white--text">TELEFONE</th>
										<th class="white--text">SEXO</th>
										<th class="white--text">DATA DE NASCIMENTO</th>
										<th class="white--text">OPÇÕES</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="user in users" :key="user.id">
										<td>{{ user.nome }}</td>
										<td>{{ user.cpf }}</td>
										<td>{{ user.email }}</td>
										<td>{{ user.telefone }}</td>
										<td>{{ exibeSexo(user.sexo) }}</td>
										<td>{{ user.dataNascimento }}</td>
										<td>
											<v-btn rounded color="pink darken-2" dark @click="model_editar(user.id)">
                                                Editar
                                            </v-btn>
											<v-btn rounded color="error" dark @click="apagar(user.id)">Apagar</v-btn>
										</td>
									</tr>
								</tbody>
							</template>
						</v-simple-table>

                        <!-- Componente de Diálogo para CREAR y EDITAR -->
                        <v-dialog v-model="formCriar" max-width="800">        
                            <v-card>
                                <v-card-title class="blue-grey darken-1 white--text">{{title}}</v-card-title>    
                                <v-card-text>            
                                    <v-form>             
                                        <v-container>
											<v-row v-show="error">
												<span style="color:red">{{error}}</span>	
											</v-row>
                                            <v-row>		
												<input v-model="user.id" hidden></input>
												<v-col cols="12" md="8">
													<v-text-field v-model="user.nome" label="Nome" solo>												
													</v-text-field>
												</v-col>											
												<v-col cols="12" md="4">
													<v-text-field v-model="user.cpf" label="CPF" solo :mask="['###.###.###-##']" >
													</v-text-field>
												</v-col>
												<v-col cols="12" md="6">
													<v-text-field v-model="user.email" type="email" label="E-mail" solo >
													</v-text-field>
												</v-col>
												<v-col cols="12" md="6">
													<v-text-field v-model="user.telefone" label="Telefone" solo>
													</v-text-field>
												</v-col>
												<v-col cols="12" md="6">										
													<v-select v-model="user.sexo" label="Género" solo :items=generos>
													</v-select>									
												</v-col>
												<v-col cols="12" md="6">
													<v-text-field v-model="user.dataNascimento" type="date" 
													label="Data de Nascimento" solo>
													</v-text-field>
												</v-col>
                                            </v-row>
                                        </v-container>            
									</v-card-text>								
									<v-card-actions>
										<v-spacer></v-spacer>
										<v-btn @click="formCriar=false">Cancelar</v-btn>
										<v-btn @click="guardar()" type="submit" color="indigo" dark>{{btn}}</v-btn>
									</v-card-actions>							
                                </v-form>
                            </v-card>
                        </v-dialog>
					</v-card>    					
				</v-main>
    		</v-app>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  		<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js" integrity="sha512-quHCp3WbBNkwLfYUMd+KwBAgpVukJu5MncuQaWXgCrfgcxCJAq/fo+oqrRKOj+UKEmyMCG3tb8RB63W+EmrOBg==" crossorigin="anonymous"></script>
  		<script	script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.all.min.js"></script>
		<script type="text/javascript">
			var url = "<?=site_url('user');?>/";
	
			new Vue({
     			el: '#crud_user',
      			vuetify: new Vuetify(),
       			data(){					
        			return{   
						generos: [{text: 'Masculino', value: 1}, {text: 'Feminino', value: 2}, {text: 'Outros', value: 3}],
            			error: false,
						users: [],
						formCriar: false,
						operacao:'',
						btn:'',
						title:'',
						user:{
							id:null,
							nome:'',
							cpf:'',
							email:'',
							telefone:'',
							sexo:null,
							dataNascimento:''
						}                 
        			}			
       			},
				created(){       
					this.exibir();
				},  
				methods:{ 
					// Inicia métodos do CRUD:
					exibir:function(){
						axios.get(url+'exibir').then(response=>{
							console.log(response.data);
							this.users = response.data;
						});
					},

					criar: function(){
						novo_user = this.user;	
                        axios.post(url+'criar', {user:novo_user}).then(response=>{
                            this.exibir()
                        });				
                    
						this.user.id=null;
                        this.user.nome="";
                        this.user.cpf="";
                        this.user.email="";  
						this.user.telefone="";  
						this.user.sexo="";  
						this.user.dataNascimento="";  				              
                    },

					editar: function(){
						editar_user = this.user;

						console.log(editar_user);
					
						console.log('NOVO USUARIO');
						console.log(editar_user);	
				        axios.post(url+'editar', {user:editar_user}).then(response=>{
                            this.exibir()
                        });

						this.user.id=null;
                        this.user.nome="";
                        this.user.cpf="";
                        this.user.email="";  
						this.user.telefone="";  
						this.user.sexo="";  
						this.user.dataNascimento=""; 			
					},

                    apagar: function(id){
                        Swal.fire({
                            title: 'Tem certeza que deseja apagar esse usuário?',   
                            confirmButtonText: `Confirmar`,                  
                            showCancelButton: true,                          
                        }).then((result) => {                
                            if(result.isConfirmed) {      
                                axios.delete(url+'apagar/'+id).then(response =>{           
                                    this.exibir();                                   
                                });      
                                Swal.fire('Eliminado!', '', 'success')
                            } else if (result.isDenied) {                  
                            }
                        });
                    },

					guardar:function(){
						valida    = this.checkForm();
						validaCPF = this.validaCPF();
						if(valida && validaCPF){
							// Criar novo usuário:
							if(this.operacao==1){
								console.log('CRIAR');
								this.criar();

							// Ediar usuário:
							}else if(this.operacao==2){
								console.log('EDITAR');
								this.editar();
							}
							this.formCriar=false;
						}
                    },		

					// Botões e formularios:
					model_criar:function(){
						this.title = 'Cadastrar novo usuário';
						this.btn = "Cadastrar";
                        this.formCriar=true;	
						this.error=false;		
				
						this.user.id=null;
                        this.user.nome="";
                        this.user.cpf="";
                        this.user.email="";  
						this.user.telefone="";  
						this.user.sexo="";  
						this.user.dataNascimento=""; 	
						// 1 - Criar:
						this.operacao = 1; 
                    },		

					model_editar:function(id){											
						axios.get(url+'get_user/'+id).then(response =>{ 
							response.data.sexo = parseInt(response.data.sexo);
							this.user = response.data; 							
							this.title = 'Editar usuário';
							this.btn = "Editar"; 										
							// 2 - Editar:
							this.operacao = 2;    
							this.formCriar = true;  
							this.error=false;		  	                 
						});                                 
                    },	

					checkForm: function(){
						if(this.user.nome && this.user.cpf && this.user.email && this.user.telefone && this.user.sexo && this.user.dataNascimento) {
							return true;
						}
						this.error = 'Todos os campos devem ser preenchidos';									
					},

					validaCPF: function() {
						var Soma;
						var Resto;
						Soma = 0;
						if(this.user.cpf == "00000000000"){
							this.error = 'CPF INVÁLIDO';		
							return false;
						}

						for (i=1; i<=9; i++) Soma = Soma + parseInt(this.user.cpf.substring(i-1, i)) * (11 - i);
						Resto = (Soma * 10) % 11;

						if ((Resto == 10) || (Resto == 11))  Resto = 0;
						if (Resto != parseInt(this.user.cpf.substring(9, 10)) ){
							this.error = 'CPF INVÁLIDO';		
							return false;
						}

						Soma = 0;
						for (i = 1; i <= 10; i++) Soma = Soma + parseInt(this.user.cpf.substring(i-1, i)) * (12 - i);
						Resto = (Soma * 10) % 11;

						if ((Resto == 10) || (Resto == 11))  Resto = 0;
						if (Resto != parseInt(this.user.cpf.substring(10, 11) ) ){
							this.error = 'CPF INVÁLIDO';		
							return false;
						}						
						return true;
					},

					exibeSexo: function(idSexo){
						idSexo = parseInt(idSexo);
						var sexo=null;
						switch(idSexo){
							case 1: sexo="Masculino"; break;
							case 2: sexo="Feminino"; break;
							case 3: sexo="Outros"; break;
						}
						return sexo;
					}
				}      
    		});
		</script>
	</body>
</html>