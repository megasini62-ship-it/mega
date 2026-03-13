          <!-- / Content -->
          </div>
          <!-- / flex-grow-1 p-4 -->
        </div>
        <!-- / flex-grow-1 d-flex flex-column -->
      </div>
    <!-- / modern-layout -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
      function successnotify(msg) {
        Toastify({ text: msg, duration: 3000, gravity: "top", position: "right",
          style: { background: "linear-gradient(135deg, #11998e, #38ef7d)", borderRadius: "10px" }
        }).showToast();
      }
      function errornotify(msg) {
        Toastify({ text: msg, duration: 3000, gravity: "top", position: "right",
          style: { background: "linear-gradient(135deg, #f093fb, #f5576c)", borderRadius: "10px" }
        }).showToast();
      }
      // Mobile sidebar
      function openSidebar() {
        document.getElementById('sidebar').style.display = 'flex';
        document.getElementById('sidebarOverlay').classList.remove('d-none');
      }
      function closeSidebar() {
        document.getElementById('sidebar').style.display = '';
        document.getElementById('sidebarOverlay').classList.add('d-none');
      }
      var closeSidebarBtn = document.getElementById('closeSidebar');
      if (closeSidebarBtn) { closeSidebarBtn.addEventListener('click', closeSidebar); }
      // Sidebar menu hover effect
      document.querySelectorAll('.menu-link').forEach(function(link) {
        link.addEventListener('mouseenter', function() {
          this.style.background = 'rgba(102,126,234,0.15)';
          this.style.color = '#a78bfa';
        });
        link.addEventListener('mouseleave', function() {
          this.style.background = '';
          this.style.color = '';
        });
      });
    </script>
    <script>
                      $('#gerar').click(function(){
                        var dominio = $('#dominiogerar').val();
                        var token = $('#tokengerar').val();
                        var tipo;
                        if (dominio == '') {
                          errornotify('Preencha o dominio');
                        } else if (token == '') {
                          errornotify('Preencha o token');
                        } else {
                          successnotify('Gerando pagamento...');
                        // Verifica qual radio button está marcado
                        if ($('#mensal').is(':checked')) {
                            tipo = 'mensal';
                        } else if ($('#anual').is(':checked')) {
                            tipo = 'anual';
                        } else if ($('#trimestral').is(':checked')) {
                            tipo = 'trimestral';
                        }
                        $.ajax({
                          url: 'api',
                          type: 'POST',
                          data: {
                            dominio: dominio,
                            token: token,
                            gerartoken: true,
                            tipo: tipo
                          },
                          dataType: 'json', // Especifica que você espera uma resposta JSON
                          success: function(data) {
    if (data && data.status == 'sucesso') {
        successnotify('Token gerado com sucesso!');
        //limpa o modal-body
        $('#modalgerartoken').find('.modal-body').html('');
        //remover modal-footer
        $('#modalgerartoken').find('.modal-footer').remove();
        //cria o novo modal-body com o qr code e o copia e cola
        var modalBody = '<div class="row"><div class="col mb-3"></div></div><div class="row"><div class="col mb-3 text-center"><label for="nameWithTitle" class="form-label"></label><img style="width: 300px;" src="data:image/png;base64,' + data.pagamento.qr_code_base64 + '" class="img-fluid qr-code" alt="QR Code" /></div></div><div class="row"><div class="col mb-3"><label for="nameWithTitle" class="form-label">Informaçes, Após pagamento ele será validado.</label><textarea class="form-control" id="dadoscompra" rows="10" placeholder="Informações copia e cola">' +
            ' *Token Gerado com Sucesso!* ' + '\n\n' +
            'Token: ' + data.token + '\n' +
            'Domínio: ' + data.dominio + '\n' +
            'Vencimento: ' + data.vencimento + '\n' +
            'Usuário: admin \n' + 
            'Senha: 12345 \n' + 
            'Tipo: ' + data.tipo + '\n' +
            'Canal Updates WhatsApp: https://whatsapp.com/channel/0029VaU9XCXDZ4LfggnwWS1U' + '\n' +
            'Canal Updates Telegram: https://t.me/painelatlas' + '\n' +
            'Documentação: https://docs.atlaspainel.com.br/' + '\n' +
            '</textarea></div></div><div class="row"><div class="col mb-3"><label for="nameWithTitle" class="form-label">Copia e cola</label><input type="text" class="form-control" id="qrcode" value="' + data.pagamento.qr_code + '" placeholder="Codigo copia e cola" /></div></div><button id="copiar" class="btn btn-primary" onclick="copyDivToClipboard()">Copiar</button>';
        $('#modalgerartoken').find('.modal-body').html(modalBody);
    } else {
        errornotify('Erro ao gerar token!');
    }
}


                        });
                      }
                      });


                    function copyDivToClipboard() {
                     let textoCopiado = document.getElementById("qrcode");
                            textoCopiado.select();
                            textoCopiado.setSelectionRange(0, 99999)
                            document.execCommand("copy");
                            successnotify('Copiado com Sucesso!');
                     
                    }


                    </script>
              <script>
            $('#button-search').click(function () {
                var pesquisa = $('#search').val();
                $.ajax({
                    url: 'api',
                    method: 'POST',
                    data: { pesquisa: pesquisa },
                    dataType: 'json',
                    success: function (json) {
                        if (json.length > 0) {
                            atualizarTabelaTokens(json);
                        } else {
                            errornotify('Nenhum resultado encontrado!')
                            
                        }
                    },
                    error: function (error) {
                        console.error('Erro ao realizar a pesquisa:', error);
                    }
                });

                function atualizarTabelaTokens(tokens) {
                    $('.table tbody tr').remove();
                    for (var i = 0; i < tokens.length; i++) {
                        var newRow = "<tr>" +
                            "<td>" + tokens[i]['token'] + "</td>" +
                            "<td>" + tokens[i]['dominio'] + "</td>" +
                            "<td>" + tokens[i]['vencimento'] + "</td>" +
                            "<td>" + "<div class='btn-group' role='group'>" +
                            "<button class='btn btn-sm btn-primary' onclick='abrirModalEditarDominio(" + tokens[i]['id'] + ")'>" +
                            "Editar" +
                            "</button>" +
                            "<button class='btn btn-sm btn-success' onclick='abrirModalrenovarDominio(" + tokens[i]['id'] + ")'>" +
                            "Renovar" +
                            "</button>" +
                            "</div>" + "</td>" +
                            "</tr>";
                        $('.table tbody').append(newRow);
                    }
                }
            });
        </script>

<script>
  $('#enviarcodigoemail').click(function () {
    var dominio = $('#dominioresgate').val();
    var token = $('#tokenresgate').val();
    var codigo = '<?php $rand = gerarToken('15'); echo $rand; ?>'
    $.ajax({
      url: 'api',
      method: 'POST',
      data: { dominio: dominio, token: token, enviaremail: true, codigo: codigo },
      dataType: 'json',
      success: function (json) {
        if (json.status == 'sucesso') {
          successnotify('Email enviado com sucesso!');
        } else {
          errornotify('Erro ao enviar email!');
        }
      },
        error: function (error) {
            console.error('Erro ao enviar email:', error);
        }
    });
    });


    $('#renovardominio').click(function () {
        var id = $('#iddominio').val();
        var dominio = $('#dominioarenovar').val();
        var token = $('#tokenrenovar').val();
        var vencimento = $('#vencimentorenovar').val();
        var tipo;
        // Verifica qual radio button está marcado
        if ($('#mensalrenovar').is(':checked')) {
            tipo = 'mensal';
        } else if ($('#anualrenovar').is(':checked')) {
            tipo = 'anual';
        } else if ($('#trimestralrenovar').is(':checked')) {
            tipo = 'trimestral';
        }
        if (tipo == null) {
            errornotify('Selecione um tipo de plano!');
            return;
        }
        successnotify('Gerando pagamento...');
        $.ajax({
            url: 'api',
            method: 'POST',
            data: {
                id: id,
                dominio: dominio,
                token: token,
                vencimento: vencimento,
                renovartoken: true,
                tipo: tipo
            },
            dataType: 'json', // Especifica que você espera uma resposta JSON
            success: function (data) {
                if (data && data.status == 'sucesso') {
                   
                    //limpa o modal-body
                    $('#modal-renovar-dominio').find('.modal-body').html('');
                    //remover modal-footer
                    $('#modal-renovar-dominio').find('.modal-footer').remove();
                    //cria o novo modal-body com o qr code e o copia e cola
                    var modalBody = '<div class="row"><div class="col mb-3"></div></div><div class="row"><div class="col mb-3 text-center"><label for="nameWithTitle" class="form-label"></label><img style="width: 300px;" src="data:image/png;base64,' + data.pagamento.qr_code_base64 + '" class="img-fluid qr-code" alt="QR Code" /></div></div><div class="row"><div class="col mb-3"><label for="nameWithTitle" class="form-label">Informações, Após pagamento ele será renovado.</label><textarea class="form-control" id="dadoscompra" rows="10" placeholder="Informaões copia e cola">' +
                     'Domínio: ' + data.dominio + '\n' +
                     'Vencimento: ' + data.vencimento + '\n' +
                     'Tipo: ' + data.tipo + '\n' +
                     'Usuário: admin \n' + 
                     'Senha: 12345 \n' + 
                     'Documentaço: https://docs.atlaspainel.com.br/' + '\n' +
                     'Grupo Updates: https://chat.whatsapp.com/ETGQKEqmQNWDhrP5j2nqLK' + '\n' +
                     '</textarea></div></div><div class="row"><div class="col mb-3"><label for="nameWithTitle" class="form-label">Copia e cola</label><input type="text" class="form-control" id="qrcode" value="' + data.pagamento.qr_code + '" placeholder="Codigo copia e cola" /></div></div><button id="copiar" class="btn btn-primary" onclick="copyDivToClipboard()">Copiar</button>';
                    $('#modal-renovar-dominio').find('.modal-body').html(modalBody);
                } else {
                    errornotify('Erro ao gerar token!');
                }
            },
            error: function (error) {
                console.error('Erro ao gerar token:', error);
            }

        });
    });
                
</script>

<script>
                    $(document).ready(function () {
                      $("#resgatar").click(function () {
                        var dominioresgate = $("#dominioresgate").val();
                        var tokenresgate = $("#tokenresgate").val();
                        $.ajax({
                          url: "api",
                          type: "POST",
                          data: {
                            dominioresgate: dominioresgate,
                            tokenresgate: tokenresgate,
                          },
                            dataType: "json",
                        success: function (json) {
                            if (json.status == "sucesso") {
                              successnotify("Token Resgatado com Sucesso!");
                              setTimeout(function () {
                                location.reload();
                              }, 1000);
                            } else if (json.status == "codigoerro") {
                              errornotify("Código de Email Incorreto!");
                            }else{
                              errornotify("Erro ao Resgatar Token!");
                            }
                            
                          },
                        });

                      });
                    });
                    
                  </script>
<script>
                      $(document).ready(function () {
                        $("#editardominio").click(function () {
                          var dominio = $("#dominioeditar").val();
                          var token = $("#tokeneditar").val();
                          $.ajax({
                            url: "api",
                            type: "POST",
                            data: {
                              dominio: dominio,
                                token: token,
                                editandodominio: true,
                            },
                            dataType: "json",
                            success: function (json) {
                                if (json.status == "sucesso") {
                                  successnotify("Dominio Editado com Sucesso!");
                                  setTimeout(function () {
                                    location.reload();
                                  }, 1000);
                                } else {
                                  errornotify("Erro ao Editar Dominio!");
                                }
                              },
                            });
                        });
                        });
                    </script>
                    <script>
    function abrirModalEditarDominio(id) {
        editardominio(id);
    }
    function editardominio(id){
        $.ajax({
            url: 'api',
            method: 'POST',
            data: { id: id, editardominio: true },
            dataType: 'json',
            success: function (json) {
                if (json.status == 'sucesso') {
                    $('#modal-editar-dominio').modal('show');
                    $('#modal-editar-dominio').find('#dominioeditar').val(json.dominio.dominio);
                    $('#modal-editar-dominio').find('#tokeneditar').val(json.dominio.token);
                } else {
                    errornotify('Erro! Você não tem permissão para editar esse domínio!');

                }
            },
            error: function (error) {
                console.error('Erro ao editar dominio:', error);
            }
        });
    }

    function abrirModalrenovarDominio(id) {
        
        renovardominio(id);
    }
    function renovardominio(id){
        $.ajax({
            url: 'api',
            method: 'POST',
            data: { id: id, renovardominio: true },
            dataType: 'json',
            success: function (json) {
                if (json.status == 'sucesso') {
                    $('#modal-renovar-dominio').modal('show');
                    $('#modal-renovar-dominio').find('#dominioarenovar').val(json.dominio.dominio);
                    $('#modal-renovar-dominio').find('#iddominio').val(json.id);
                    $('#modal-renovar-dominio').find('#vencimentorenovar').val(json.dominio.vencimento);
                    successnotify('Sucesso! Você tem permisso para renovar esse domínio!');
                } else {
                    errornotify('Erro! Você não tem permissão para editar esse domínio!');
                }
            },
            error: function (error) {
                console.error('Erro ao editar dominio:', error);
            }
        });
    }
</script>
<script>
                    $(document).ready(function() {
                      $("#geraradmintoken").click(function() {
                        var dominio = $("#dominiogeraradmin").val();
                        var token = $("#tokengeraradmin").val();
                        var mensal = $("#mensaladmin").is(":checked");
                        var trimestral = $("#trimestraladmin").is(":checked");
                        var anual = $("#anualadmin").is(":checked");
                        var tipo;
                        if ($('#mensaladmin').is(':checked')) {
                            tipo = 'mensal';
                        } else if ($('#anualadmin').is(':checked')) {
                            tipo = 'anual';
                        } else if ($('#trimestraladmin').is(':checked')) {
                            tipo = 'trimestral';
                        }
                        if (dominio == "") {
                          errornotify("Preencha o dominio");
                        } else if (token == "") {
                          errornotify("Preencha o token");
                        } else if (tipo == null) {
                          errornotify("Selecione um tipo de plano");
                        } else {
                          successnotify("Gerando token...");
                          $.ajax({
                            url: "api",
                            type: "POST",
                            data: {
                              dominio: dominio,
                              token: token,
                              gerartokenadmin: true,
                              tipo: tipo,
                            },
                            dataType: "json",
                            success: function (data) {
                              if (data && data.status == "sucesso") {
                                successnotify("Token gerado com sucesso!");
                                //limpa o modal-body
                                $("#modaladmincriartoken")
                                  .find(".modal-body")
                                  .html("");
                                //remover modal-footer
                                $("#modaladmincriartoken")
                                  .find(".modal-footer")
                                  .remove();
                                //cria o novo modal-body com o token, dominio e o vencimento
                                var modalBody =
                                  '<div class="row"><div class="col mb-3"></div></div><div class="row"><div class="col mb-3"><label for="nameWithTitle" class="form-label">Informações.</label><textarea class="form-control" id="dadoscompra" rows="10" placeholder="Informações copia e cola">' +
                                  "Token: " +
                                  data.token +
                                  "\n" +
                                  "Domínio: " +
                                  data.dominio +
                                  "\n" +
                                  "Vencimento: " +
                                  data.vencimento +
                                  "\n" +
                                  'Usuário: admin \n' + 
                                  'Senha: 12345 \n' + 
                                  "Tipo: " +
                                  data.tipo +
                                  "\n" +
                                  'Documentação: https://docs.atlaspainel.com.br/' + '\n' +
                                  "Grupo Updates: https://chat.whatsapp.com/ETGQKEqmQNWDhrP5j2nqLK" +
                                  "\n" +
                                  "</textarea></div></div>";
                                $("#modaladmincriartoken")
                                  .find(".modal-body")
                                  .html(modalBody);
                              } else {
                                errornotify("Erro ao gerar token!");
                              }
                            },
                            error: function (error) {
                              console.error("Erro ao gerar token:", error);
                            },
                          });
                        }
                      });
                    });

                  </script>
</body>
</html>
