<style>
    .nav-item .nav-link.active {
        background-color: #919733; /* Substitua "red" pela cor desejada */
    }

    /* CSS para esconder o submenu do menu "Calendário" por padrão */
    #menu-calendario .dropdown-menu {
        display: none;
    }

    /* CSS para exibir o submenu quando o menu "Calendário" é focado ou quando o mouse está sobre ele */
    #menu-calendario:hover .dropdown-menu,
    #menu-calendario:focus .dropdown-menu {
        display: block;
    }
    .submenu-item {
        margin-left: 20px; /* Ajuste esta margem conforme necessário */
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link
    {
        background-color: #888C00 !important; /* Defina a cor que deseja para links clicados */
    }
    /*.submenu-item:active {
        background-color: #ff0000 !important;  Substitua #ff0000 pela cor desejada 
         Outros estilos que você deseja aplicar quando o item do menu está ativo 
    }
    .active-menu {
        background-color: #ff0000;  Substitua 'your-color' pela cor desejada 
    }*/
</style>

<aside class="main-sidebar elevation-4" style="background-color: whitesmoke;">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <p class="nav-link" style="color: #888C00 !important; margin-top: 10px; font-size: 15px"> Plataforma Digital Colaborativa</p>
       <!-- Sidebar Menu -->
        <nav class="mt-2 teste">
            <?php
            // Verifique as permissões do usuário
            $isAdmin = Yii::$app->user->isGuest ? false : Yii::$app->user->can("Permissão de Administrador");
            $resultadosAtivo = (Yii::$app->controller->id === 'site' && ((Yii::$app->controller->action->id === 'index') || (Yii::$app->controller->action->id === 'resultadosagricultura') || (Yii::$app->controller->action->id === 'resultadosnutricao') || (Yii::$app->controller->action->id === 'resultadosagua') || (Yii::$app->controller->action->id === 'resultadosreforcoinstitucional')));
            $coberturaAtivo = (Yii::$app->controller->id === 'site' && ((Yii::$app->controller->action->id === 'fresan') || (Yii::$app->controller->action->id === 'fresancunene') || (Yii::$app->controller->action->id === 'fresanhuila') || (Yii::$app->controller->action->id === 'fresannamibe')));
            $calendarioAtivo = (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'calendario2');

            // Inicie uma lista de itens de menu



            $menuItems[] = ['label' => 'Resultados', 'url' => ['/site/index']];
            if ($resultadosAtivo) {

                $menuItems[] = ['label' => 'Agricultura', 'url' => ['/site/resultadosagricultura'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
                $menuItems[] = ['label' => 'Nutrição', 'url' => ['/site/resultadosnutricao'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
                $menuItems[] = ['label' => 'Água', 'url' => ['/site/resultadosagua'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
                $menuItems[] = ['label' => 'Reforço Institucional', 'url' => ['site/resultadosreforcoinstitucional'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
            }

            // ['label' => 'Resultados Secundários', 'url' => ['/Monitoria/index2']],
            $menuItems[] = ['label' => 'Cobertura', 'url' => ['/site/fresan']];
            if ($coberturaAtivo) {
                $menuItems[] = ['label' => 'Cunene', 'url' => ['/site/fresancunene'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
                $menuItems[] = ['label' => 'Huíla', 'url' => ['/site/fresanhuila', 'area' => 'Huila'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];
                $menuItems[] = ['label' => 'Namibe', 'url' => ['/site/fresannamibe', 'area' => 'Namibe'], 'iconStyle' => 'far', 'options' => ['class' => 'submenu-item']];

                // ['label' => 'Interface Pública', 'url' => Yii::$app->urlManagerFrontend->createUrl('/site/index')],
            }
            $menuItems[] = ['label' => 'Beneficiários', 'url' => ['/site/beneficiario']];
            $menuItems[] = ['label' => 'Galeria', 'url' => ['site/galeria']];
            $menuItems[] = ['template' => '<hr>'];
            $menuItems[] = ['label' => 'Calendário', 'url' => ['/site/calendario2']];
            // Adicione itens relacionados ao calendário se o calendário estiver ativo
            if ($calendarioAtivo) {
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #999900;"></span> Agricultura e Pecuária', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Agricultura e Pecuária'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #cccc33;"></span> Nutrição', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Nutrição'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #00c3ff;"></span> Água', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Água'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #003399;"></span> Reforço Institucional', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Reforço Institucional'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #71b13c;"></span> Coordenação', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Coordenação'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: #663399;"></span> Subvenções/M&A', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'M&A'],
                    'encode' => false,
                    'options' => ['class' => 'pb-(-5)', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
                $menuItems[] = [
                    'label' => '<span class="event-color" style="background-color: black;"></span> Outras', 'icon' => 'none',
                    'url' => ['site/calendario2', 'area' => 'Outra'],
                    'encode' => false,
                    'options' => ['class' => 'pb-20', 'class' => 'submenu-item'], // Adiciona classe de bootstrap para espaço inferior (pb = padding-bottom)
                ];
            }
            $menuItems[] = ['label' => 'Lista de Eventos', 'url' => ['event/index']];
            $menuItems[] = ['label' => 'Contactos', 'url' => ['/contacto']];
            $menuItems[] = ['template' => '<hr>'];

            //           $menuItems [] =//        [
//            'label' => 'Reportar Dados',
//            'items' => [
//                ['label' => 'Login', 'url' => ['/site/login'], 'visible' => Yii::$app->user->isGuest],
//                ['label' => 'COMPONENTE I', 'header' => true],
//                ['label' => 'Agricultura e Pecuária', 'url' => ['/grupo/index']],
//                ['label' => 'COMPONENTE II', 'header' => true],
//                [
//                    'label' => 'Nutrição',
//                    'items' => [
//                        ['label' => 'Demostrações Culinárias', 'url' => ['/demostracoesculinarias/index']],
//                        ['label' => 'Rastreio', 'url' => ['/rastreio/index']],
//                        ['label' => 'Capacitações Técnicos de Saúde', 'url' => ['/profissionaissaude/index']],
//                        ['label' => 'Pacote Pedagógico FRESAN', 'url' => ['/pacotepedagfresan/index']],
//                        ['label' => 'Suplementação', 'url' => ['/suplementacao/index']],
//                        ['label' => 'Merenda Escolar', 'url' => ['/merendaescolar/index']],
//                        ['label' => 'Capacitação', 'url' => ['/capacitacao/index']],
//                        ['label' => 'Materiais', 'url' => ['/materiais/index']],
//                        ['label' => 'Supervisão', 'url' => ['/supervisao/index']],
//                    ],
//                ],
//                ['label' => 'Água', 'url' => ['/agua/index']],
//                ['label' => 'COMPONENTE III', 'header' => true],
//                ['label' => 'Reforço Institucional', 'url' => ['/reforcoinstitucional/index']],
//                ['label' => 'SUPLEMENTOS', 'header' => true],
//                ['label' => 'Comunicação', 'url' => ['/doccomunicacao/index']],
//                ['label' => 'Eventos', 'url' => ['/eventos/index']],
//            ],
//    ];

            if ($isAdmin) {
                $menuItems [] = [
                            'label' => 'Reportar Dados',
                            'items' => [
                                ['label' => 'Login', 'url' => ['/site/login'], 'visible' => Yii::$app->user->isGuest],
                                ['label' => 'COMPONENTE I', 'header' => true],
                                ['label' => 'Agricultura e Pecuária', 'url' => ['/grupo/index']],
                                ['label' => 'COMPONENTE II', 'header' => true],
                                [
                                    'label' => 'Nutrição',
                                    'items' => [
                                        ['label' => 'Demostrações Culinárias', 'url' => ['/demostracoesculinarias/index']],
                                        ['label' => 'Rastreio', 'url' => ['/rastreio/index']],
                                        ['label' => 'Capacitações Técnicos de Saúde', 'url' => ['/profissionaissaude/index']],
                                        ['label' => 'Pacote Pedagógico FRESAN', 'url' => ['/pacotepedagfresan/index']],
                                        ['label' => 'Suplementação', 'url' => ['/suplementacao/index']],
                                        ['label' => 'Merenda Escolar', 'url' => ['/merendaescolar/index']],
                                        ['label' => 'Capacitação', 'url' => ['/capacitacao/index']],
                                        ['label' => 'Materiais', 'url' => ['/materiais/index']],
                                        ['label' => 'Supervisão', 'url' => ['/supervisao/index']],
                                    ],
                                ],
                                ['label' => 'Água', 'url' => ['/agua/index']],
                                ['label' => 'COMPONENTE III', 'header' => true],
                                ['label' => 'Reforço Institucional', 'url' => ['/reforcoinstitucional/index']],
                                ['label' => 'SUPLEMENTOS', 'header' => true],
                                ['label' => 'Comunicação', 'url' => ['/doccomunicacao/index']],
                                ['label' => 'Eventos', 'url' => ['/eventos/index']],
                            ],
                ];
            }
            else {
                
                $menuItems[] = ['label' => 'Reportar Dados', 'url' => ['site/emconstrucao']];
            }
            $menuItems[] = ['template' => '<hr>'];

// Agrupe os itens "Resumo Monitoria", "Resumo Recomendações" e "Resumo Boas Práticas" sob o item "Resumo"
//$menuItems[] =  ['label' => 'Resultados Secundários', 'url' => ['/monitoria/index2']];
            $menuItems[] = ['label' => 'Recomendações', 'url' => ['site/emconstrucao']];
            $menuItems[] = ['label' => 'Boas Práticas', 'url' => ['site/emconstrucao']];
// Os outros itens finais do menu
            $menuItems[] = ['label' => 'Biblioteca', 'url' => ['site/emconstrucao']];
// $menuItems[] = ['label' => 'Galeria', 'url' => ['site/galeria']];
            $menuItems[] = ['template' => '<hr>'];

// Itens de administração (se aplicável)...
            // Adicione itens de administração se o usuário for administrador
            if ($isAdmin) {
                $menuItems[] = [
                    'label' => 'Administração',
                    'items' => [
                        [
                            'label' => 'Configurações',
                            'items' => [
                                ['label' => 'Provincia', 'url' => ['/provincia/index'], 'iconStyle' => 'far'],
                                ['label' => 'Municipio', 'url' => ['/municipio/index'], 'iconStyle' => 'far'],
                                ['label' => 'Comuna', 'url' => ['/comuna/index'], 'iconStyle' => 'far'],
                                ['label' => 'Localidade', 'url' => ['/localidade/index'], 'iconStyle' => 'far'],
                                ['label' => 'Unidade', 'url' => ['/unidade/index'], 'iconStyle' => 'far'],
                                ['label' => 'Class.Doc.Artigo', 'url' => ['/classificacaodocumentoartigo/index'], 'iconStyle' => 'far'],
                                ['label' => 'Finalidade.Agri', 'url' => ['/finalidade/index'], 'iconStyle' => 'far'],
                                ['label' => 'Culturas Providas', 'url' => ['/culturasprovidas/index'], 'iconStyle' => 'far'],
                                ['label' => 'Metas', 'url' => ['/metas/index'], 'iconStyle' => 'far'],
                            ],
                        ],
                        ['label' => 'Administrar Usuários', 'url' => ['/user/index'], 'iconStyle' => 'far'],
                        ['label' => 'Rotas', 'url' => ['/rbac/route'], 'iconStyle' => 'far'],
                        ['label' => 'Permissão Rotas', 'url' => ['/rbac/permission'], 'iconStyle' => 'far'],
                        ['label' => 'Perfil', 'url' => ['/rbac/role'], 'iconStyle' => 'far'],
                        ['label' => 'Atribuir Perfil a Usuário', 'url' => ['/rbac/assignment'], 'iconStyle' => 'far'],
                    ],
                ];
            }

            // Renderize o menu com todos os itens definidos
            echo hail812\adminlte\widgets\Menu::widget(['items' => $menuItems]);
            ?>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>