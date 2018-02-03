<style>
    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    tr.hover {
        cursor: pointer;
        /* whatever other hover styles you want */
    }

    #example {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
</style>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

<div class="container">
    <h1>Quotation List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a>Quotation List</a>
        </li>
    </ol>

    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Send</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($quotation as $q){ ?>
            <tr class="click">
                <td>
                    <a href="<?php echo base_url().'index.php/Quotation/buyer_quotation_detail?id_quotation='.$q->IdQuotation; ?>">To:
                        <?php echo $q->CompanyName  ?>
                    </a>
                </td>
                <td>Pembelian
                    <?php echo $q->Name  ?>
                </td>
                <td>
                    <?php echo trim(substr($q->Content,0,50))." <b>...</b>" ?>
                </td>
                <td>
                    <?php echo $q->DateSend  ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
        $('.click').click(function () {
            window.location = $(this).find('a').attr('href');
        }).hover(function () {
            $(this).toggleClass('hover');
        });
    });
</script>