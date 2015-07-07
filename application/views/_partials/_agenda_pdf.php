
        <header style="text-align: center;">
            <img src="<?php echo site_url('img/pdf-logo.jpg'); ?>" alt="Varadouro Cultural" style="display: block; margin: auto;" width="110" height="107">
            <h1 style="margin: 0; font-family: Helvetica, Arial, sans-serif;"><?php echo $usuario->nome . ' ' . $usuario->sobrenome; ?></h1>
            <h3 style="margin: 0; font-family: Helvetica, Arial, sans-serif;">Agenda Cultural</h3>
        </header>

        <main>
            <table style="display: block; margin: auto;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black;">Evento</th>
                        <th style="border: 1px solid black;">Local</th>
                        <th style="border: 1px solid black;">Data</th>
                        <th style="border: 1px solid black;">Horário</th>
                        <th style="border: 1px solid black;">Preço</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                        <tr>
                            <td style="border: 1px solid black;"><?php echo $evento->titulo; ?></td>
                            <td style="border: 1px solid black;"><?php echo $evento->espaco->nome_espaco; ?></td>
                            <td style="border: 1px solid black;"><?php echo $evento->informacoes_datas; ?></td>
                            <td style="border: 1px solid black;"><?php echo $evento->informacoes_horarios; ?></td>
                            <td style="border: 1px solid black;"><?php echo $evento->informacoes_valores; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>