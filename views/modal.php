<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Resgatar Token</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Insira o dominio</label>
                                    <input
                                      type="text"
                                      id="dominioresgate"
                                      class="form-control"
                                      placeholder="Insira o dominio" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Insira o Token</label>
                                    <input
                                      type="text"
                                      id="tokenresgate"
                                      class="form-control"
                                      placeholder="Insira o Token" />
                                  </div>
                                </div>
                                <div class="row">
                                  
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id="resgatar" class="btn btn-primary">Resgatar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="modal fade" id="modal-editar-dominio" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Editar Dominio</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Seu Dominio</label>
                                    <input
                                      type="text"
                                      id="dominioeditar"
                                      class="form-control"
                                      placeholder="Seu Dominio" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Token</label>
                                    <input
                                      type="text"
                                      id="tokeneditar"
                                      class="form-control"
                                      placeholder="Seu Dominio" disabled />
                                  </div>
                                </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id="editardominio" class="btn btn-primary">Editar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
                    <div class="modal fade" id="modal-renovar-dominio" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Renovar Dominio</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="card-body">
                              <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">ID dominio</label>
                                    <input
                                      type="text"
                                      id="iddominio"
                                      class="form-control"
                                      placeholder="id dominio" disabled />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Dominio a ser renovado</label>
                                    <input
                                      type="text"
                                      id="dominioarenovar"
                                      class="form-control"
                                      placeholder="Seu Dominio" disabled />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Vencimento Atual</label>
                                    <input
                                      type="text"
                                      id="vencimentorenovar"
                                      class="form-control"
                                      placeholder="Vencimento" disabled />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                        <span class="custom-option-body">
                                          <i class="bx bx-rocket"></i>
                                          <span class="custom-option-title">Mensal</span>
                                          <small>Plano Mensal 1 Mês (30 Dias) R$ 30,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="mensalrenovar"
                                          checked />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                        <span class="custom-option-body">
                                          <i class="bx bx-user"></i>
                                          <span class="custom-option-title"> Trimestral </span>
                                          <small> Plano Trimestral 3 Meses R$ 70,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="trimestralrenovar" />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                        <span class="custom-option-body">
                                          <i class="bx bx-crown"></i>
                                          <span class="custom-option-title"> Anual </span>
                                          <small> Plano Anual 12 Meses R$ 150,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="anualrenovar" />
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <br>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id="renovardominio" class="btn btn-primary">Renovar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
                    <div class="modal fade" id="modal-pagamento-dados" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Pagamento</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="card-body">
                              
                                
                              <br>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    

                    <?php if ($_SESSION['perm'] == 'SIM') { ?>
                    <div class="modal fade" id="modaladmincriartoken" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Gerar Token Admin</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Insira o dominio</label>
                                    <input
                                      type="text"
                                      id="dominiogeraradmin"
                                      class="form-control"
                                      placeholder="Insira o dominio" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Token</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      id="tokengeraradmin"
                                      value="<?php $rand = gerarToken('15'); echo $rand; ?>"
                                      placeholder="Insira o Token" />
                                  </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                        <span class="custom-option-body">
                                          <i class="bx bx-rocket"></i>
                                          <span class="custom-option-title">Mensal</span>
                                          <small>Plano Mensal 1 Mês (30 Dias)  R$ 30,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="mensaladmin"
                                          checked />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                        <span class="custom-option-body">
                                          <i class="bx bx-user"></i>
                                          <span class="custom-option-title"> Trimestral </span>
                                          <small> Plano Trimestral 3 Meses R$ 70,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="trimestraladmin" />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                        <span class="custom-option-body">
                                          <i class="bx bx-crown"></i>
                                          <span class="custom-option-title"> Anual </span>
                                          <small> Plano Anual 12 Meses R$ 150,00 </small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="anualadmin" />
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id='geraradmintoken' class="btn btn-primary">Gerar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                  
                    <?php } ?>

                  


                    <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
                    <div class="modal fade" id="modalgerartoken" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Gerar Token</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Insira o dominio</label>
                                    <input
                                      type="text"
                                      id="dominiogerar"
                                      class="form-control"
                                      placeholder="Insira o dominio" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Token</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      id="tokengerar"
                                      value="<?php $rand = gerarToken('15'); echo $rand; ?>"
                                      placeholder="Insira o Token" />
                                  </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                        <span class="custom-option-body">
                                          <i class="bx bx-rocket"></i>
                                          <span class="custom-option-title">Mensal</span>
                                          <small>Plano Mensal 1 Mês (30 Dias) R$ 30,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="mensal"
                                          checked />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                        <span class="custom-option-body">
                                          <i class="bx bx-user"></i>
                                          <span class="custom-option-title"> Trimestral </span>
                                          <small> Plano Trimestral 3 Meses R$ 70,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="trimestral" />
                                      </label>
                                    </div>
                                  </div>
                                  <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon">
                                      <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                        <span class="custom-option-body">
                                          <i class="bx bx-crown"></i>
                                          <span class="custom-option-title"> Anual </span>
                                          <small> Plano Anual 12 Meses R$ 150,00</small>
                                        </span>
                                        <input
                                          name="customRadioIcon"
                                          class="form-check-input"
                                          type="radio"
                                          value=""
                                          id="anual" />
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id='gerar' class="btn btn-primary">Gerar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <?php } ?>




                    
                    