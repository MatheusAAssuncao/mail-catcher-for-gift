</div>
<div class="footer">&copy; &nbsp; Desenvolvido por <a href="https://www.salesgroup.pt/" target="_blank" style="text-decoration:none">Sales Group</a></div>
<script>
    $('[name="phone"]').keyup(function() {
        $(this).val(this.value.replace(/\D/g, ''));
    });

    if ($('.success_message').text().length > 0) {
        $('[name="code"]').val('');
    }

    preencher = function() {
        $('[name="name"]').val('Matheus');
        $('[name="email"]').val('matheus@hotmail.com');
        $('[name="phone"]').val('932321446');
        $('[name="term"]').prop('checked', true);
    }
</script>
</body>
</html>
