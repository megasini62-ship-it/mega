<?php
session_start();
include 'header.php'; 

?>

<div class="card mb-4">
                    <h5 class="card-header">Trocar senha</h5>
                    <div class="card-body">
                      <form id="account" method="POST">
                        <div class="row">
                          <div class="mb-3 col-md-6 form-password-toggle">
                            <label class="form-label" for="currentPassword">Senha atual</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                name="currentPassword"
                                id="currentPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="mb-3 col-md-6 form-password-toggle">
                            <label class="form-label" for="newPassword">Nova senha</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                id="newPassword"
                                name="newPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>

                          <div class="mb-3 col-md-6 form-password-toggle">
                            <label class="form-label" for="confirmPassword">Confirmar senha</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                name="confirmPassword"
                                id="confirmPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                              <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                          </div>
                          <div class="col-12 mb-4">
                                    <p class="fw-semibold mt-2">Requisitos de Senha:</p>
                                    <ul class="ps-3 mb-0">
                                        <li class="mb-1">Mínimo de 8 caracteres - quanto mais, melhor</li>
                                        <li class="mb-1">Pelo menos um caractere minúsculo</li>
                                        <li>Pelo menos um número, símbolo ou caractere de espaço em branco</li>
                                    </ul>
                                </div>

                            </ul>
                          </div>
                          <div class="col-12 mt-1">
                            <button type="submit" class="btn btn-primary me-2">Salvar</button>
                            <button type="reset" class="btn btn-label-secondary">Cancelar</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  <?php 
                  include 'modal.php';
                  if (isset($_POST['currentPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
                          //verifica se a new password é igual a confirm password
                            if ($_POST['newPassword'] != $_POST['confirmPassword']) {
                                echo "<script>errornotify('As senhas não coincidem!');</script>";
                            } else {
                                $result = trocarsenha($_SESSION['id'], $_POST['currentPassword'], $_POST['newPassword'], $_POST['confirmPassword']);
                                if ($result !== false) {
                                    echo "<script>successnotify('Senha alterada com sucesso!');</script>";
                                } else {
                                    echo "<script>errornotify('Senha atual incorreta!');</script>";
                                }
                            }
                        }
                          
include 'footer.php'; ?>
