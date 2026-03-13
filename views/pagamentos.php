<?php
session_start();
include 'header.php'; 
include 'modal.php';
if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') {
    $pagamentos = consultapagamentos($_SESSION['email']);
    $texto = 'Lista de pagamentos dos ultimos';
    $vendido = consultarPagamentosAprovados($_SESSION['email']);
}else{
    echo "<script>window.location.href = 'home';</script>";
    exit;
}

?>
<div class="content-wrapper">

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">Atlas /</span> Pagamentos
              </h4>
<div class="card">
                <h5 class="card-header"><?php echo $texto; ?></h5>
                <h6 class="card-header">Total vendido nos ultimas 30 Dias: R$ <?php echo $vendido; ?></h6>

                <!-- input pesquisa -->
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar" aria-label="Pesquisar" aria-describedby="button-addon2" id='search'>
                        <button class="btn btn-outline-primary" type="button" id="button-search" >Pesquisar</button>
                    </div>
                    <div class="card-datatable table-responsive">
                  <table class="dt-advanced-search table table-bordered">
        <thead>
            <tr>
                <th>Dominio</th>
                <th>Tipo</th>
                <th>Hora</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagamentos as $pagamento) { ?>
                <tr>
                    <td><?php echo $pagamento['dominio']; ?></td>
                    <td><?php echo $pagamento['descricao']; ?></td>
                    <td><?php //converter data
                    $data = new DateTime($pagamento['data_pagamento']);
                    echo $data->format('d/m/Y H:i:s'); ?></td>
                    <td>
                    <?php if ($pagamento['status'] == 'Pendente' && $pagamento['data_pagamento'] < date('Y-m-d H:i:s')) { ?>
                        <span class="badge bg-warning">Atrasado</span>
                    <?php } elseif ($pagamento['status'] == 'Pendente') { ?>
                        <span class="badge bg-warning">Pendente</span>
                    <?php } elseif ($pagamento['status'] == 'Aprovado') { ?>
                        <span class="badge bg-success">Aprovado</span>
                    <?php } elseif ($pagamento['status'] == 'Cancelado') { ?>
                        <span class="badge bg-danger">Cancelado</span>
                    <?php } ?>
    <div class="btn-group" role="group">
        <?php if ($pagamento['status'] == 'Pendente' && $pagamento['data_pagamento'] < date('Y-m-d H:i:s')) { ?>
            <button class="btn btn-sm btn-success" onclick="abrirmodalqrcode('<?php echo $pagamento['qr_code_copia']; ?>', '<?php echo $pagamento['qr_code_base64']; ?>', '<?php echo $pagamento['dominio']; ?>', '<?php echo $pagamento['valor']; ?>' )">
                Pagar
</button>

        <?php } ?>
    </div>
</td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>

</div>
</div>

<hr class="my-5" />
</div>

<?php include 'footer.php'; ?>
<script>
    function abrirmodalqrcode(qrcode, qrcodebase64, dominio, valor) {
        $('#modal-pagamento-dados').modal('show');
        $('#modal-pagamento-dados').find('.modal-body').html('');
        $('#modal-pagamento-dados').find('.modal-footer').remove();
        $('#modal-pagamento-dados').find('.modal-body').append('<div class="alert alert-alert" role="alert" style="text-align: center; font-size: 18px;">' +
            '<div class="divider divider-success">' +
            '<strong class="divider-text" style="font-size: 20px;">INFORMAÇÕES</strong>' +
            '</div>' +
            '<p>Valor a Pagar: ' + valor + ' R$</p>' +
            '<p>Domínio: ' + dominio + '</p>' +
            '<img style="width: 160px;" class="qr_code" src="data:image/png;base64,' + qrcodebase64 + '">' +
            '<hr>' +
            '<input type="text" name="texto" id="qrcode" class="form-control" value="' + qrcode + '">' +
            '<br>' +
            '<div id="tempo-restante" style="text-align: center; font-size: 18px;"></div>' +
            '</div>' +
            '<button type="button" class="btn btn-primary" onclick="copyDivToClipboard()">Copiar</button>');
        $('#modal-pagamento-dados').find('.modal-body').append('<div class="modal-footer">' +
            '<div class="btn-group dropup mr-1 mb-1">' +
            '</div>' +
            '</div>');


    }

    function copyDivToClipboard() {
        var range = document.createRange();
        range.selectNode(document.getElementById("qrcode"));
        window.getSelection().removeAllRanges(); // clear current selection
        window.getSelection().addRange(range); // to select text
        document.execCommand("copy");
        window.getSelection().removeAllRanges(); // to deselect
        successnotify('Copiado com sucesso!');
    }

</script>