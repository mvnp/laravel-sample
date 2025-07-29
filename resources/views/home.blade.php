<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consulta de Estabelecimentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .results-container {
            position: relative;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-search"></i> 
                            Consulta de Estabelecimentos
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Search Form -->
                        <form id="searchForm">
                            @csrf
                            
                            <!-- Alert container for messages -->
                            <div id="alertContainer"></div>

                            <div class="row mb-3">
                                <!-- Nome Fantasia -->
                                <div class="col-md-6">
                                    <label for="fantasia" class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" id="fantasia" name="fantasia" placeholder="Digite parte do nome fantasia">
                                </div>

                                <!-- CNAE Principal -->
                                <div class="col-md-6">
                                    <label for="cnae_principal" class="form-label">CNAE Principal</label>
                                    <select class="form-select select2" id="cnae_principal" name="cnae_principal">
                                        <option value="">Selecione...</option>
                                        @foreach($cnaes as $cnae)
                                            <option value="{{ $cnae->codigo }}">
                                                {{ $cnae->codigo }} - {{ $cnae->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- CNPJ -->
                                <div class="col-md-4">
                                    <label for="cnpj" class="form-label">CNPJ Básico (8 dígitos)</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="12345678" maxlength="8">
                                </div>

                                <!-- CNAE Secundária -->
                                <div class="col-md-8">
                                    <label for="cnae_secundaria" class="form-label">CNAE Secundária</label>
                                    <select class="form-select select2" id="cnae_secundaria" name="cnae_secundaria">
                                        <option value="">Selecione...</option>
                                        @foreach($cnaes as $cnae)
                                            <option value="{{ $cnae->codigo }}">
                                                {{ $cnae->codigo }} - {{ $cnae->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- CEP -->
                                <div class="col-md-4">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="12345-678">
                                </div>

                                <!-- Estado -->
                                <div class="col-md-4">
                                    <label for="uf" class="form-label">Estado</label>
                                    <select class="form-select" id="uf" name="uf">
                                        <option value="">Selecione...</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->sigla }}">
                                                {{ $estado->descricao }} ({{ $estado->sigla }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Município -->
                                <div class="col-md-4">
                                    <label for="municipio" class="form-label">Município</label>
                                    <select class="form-select" id="municipio" name="municipio" disabled>
                                        <option value="">Selecione um estado primeiro...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Situação Cadastral -->
                                <div class="col-md-3">
                                    <label for="situacao" class="form-label">Situação Cadastral</label>
                                    <select class="form-select" id="situacao" name="situacao">
                                        <option value="">Todas</option>
                                        <option value="01">Nula</option>
                                        <option value="02">Ativa</option>
                                        <option value="03">Suspensa</option>
                                        <option value="04">Inapta</option>
                                        <option value="08">Baixada</option>
                                    </select>
                                </div>

                                <!-- Capital Social -->
                                <div class="col-md-6">
                                    <label for="capital_entre" class="form-label">Capital Social (Entre)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="capital_inicial" 
                                               placeholder="Valor mínimo" step="0.01">
                                        <span class="input-group-text">até</span>
                                        <input type="number" class="form-control" id="capital_final" 
                                               placeholder="Valor máximo" step="0.01">
                                        <input type="hidden" id="capital_entre" name="capital_entre">
                                    </div>
                                </div>

                                <!-- Limite de Resultados -->
                                <div class="col-md-3">
                                    <label for="limit" class="form-label">Limite de Resultados</label>
                                    <select class="form-select" id="limit" name="limit">
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="200">200</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="searchBtn">
                                            <i class="bi bi-search"></i> <span id="searchBtnText">Pesquisar</span>
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearForm">
                                            <i class="bi bi-arrow-clockwise"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Container -->
                <div class="results-container">
                    <!-- Loading Overlay -->
                    <div class="loading-overlay d-none" id="loadingOverlay">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <div class="mt-2">Pesquisando estabelecimentos...</div>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div id="resultsContainer" style="display: none;">
                        <div class="card shadow-sm mt-4 fade-in">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-table"></i> 
                                    <span id="resultsTitle">Resultados Encontrados</span>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>CNPJ</th>
                                                <th>Razão Social</th>
                                                <th>Nome Fantasia</th>
                                                <th>Situação</th>
                                                <th>CNAE Principal</th>
                                                <th>UF</th>
                                                <th>Município</th>
                                                <th>Capital Social</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resultsTableBody">
                                            <!-- Results will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div id="noResultsContainer" style="display: none;">
                        <div class="card shadow-sm mt-4 fade-in">
                            <div class="card-body text-center">
                                <i class="bi bi-search display-1 text-muted"></i>
                                <h5 class="mt-3">Nenhum resultado encontrado</h5>
                                <p class="text-muted">Tente ajustar os filtros de pesquisa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Selecione...',
                allowClear: true
            });

            // Handle UF change to load municipalities
            $('#uf').change(function() {
                const uf = $(this).val();
                const municipioSelect = $('#municipio');
                
                if (uf) {
                    municipioSelect.prop('disabled', true).html('<option value="">Carregando...</option>');
                    
                    $.ajax({
                        url: '{{ route("municipios.index") }}',
                        method: 'GET',
                        data: { uf: uf },
                        success: function(data) {
                            municipioSelect.empty().append('<option value="">Selecione...</option>');
                            
                            // Handle both array and object responses
                            const municipios = data.data || data;
                            
                            $.each(municipios, function(i, municipio) {
                                const codigo = municipio.codigo || municipio.id;
                                const nome = municipio.descricao || municipio.nome;
                                municipioSelect.append(`<option value="${codigo}">${nome}</option>`);
                            });
                            
                            municipioSelect.prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading municipios:', error);
                            showAlert('Erro ao carregar municípios. Tente novamente.', 'danger');
                            municipioSelect.empty()
                                .append('<option value="">Erro ao carregar...</option>')
                                .prop('disabled', true);
                        }
                    });
                } else {
                    municipioSelect.empty()
                        .append('<option value="">Selecione um estado primeiro...</option>')
                        .prop('disabled', true);
                }
            });

            // Handle capital range inputs
            $('#capital_inicial, #capital_final').on('input', function() {
                const inicial = $('#capital_inicial').val();
                const final = $('#capital_final').val();
                
                if (inicial && final) {
                    $('#capital_entre').val(`${inicial},${final}`);
                } else {
                    $('#capital_entre').val('');
                }
            });

            // Handle form submission with AJAX
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                performSearch();
            });

            // Clear form
            $('#clearForm').click(function() {
                clearForm();
            });

            // Format CNPJ input
            $('#cnpj').on('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 8);
            });

            // Format CEP input
            $('#cep').on('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length >= 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
                this.value = value;
            });

            // AJAX Search Function
            function performSearch() {
                const formData = $('#searchForm').serialize();
                
                // Show loading
                showLoading(true);
                hideResults();
                clearAlerts();
                
                // Update button state
                $('#searchBtn').prop('disabled', true);
                $('#searchBtnText').text('Pesquisando...');
                
                $.ajax({
                    url: '{{ route("estabelecimentos.index") }}',
                    method: 'GET',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Search response:', response);
                        
                        // Handle different response formats
                        const results = response.data || response;
                        const total = response.total || (Array.isArray(results) ? results.length : 0);
                        
                        if (total > 0) {
                            populateResults(results, total);
                            showResults();
                        } else {
                            showNoResults();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                        console.error('Response:', xhr.responseText);
                        
                        let errorMessage = 'Erro ao realizar a pesquisa. Tente novamente.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join(', ');
                        }
                        
                        showAlert(errorMessage, 'danger');
                        showNoResults();
                    },
                    complete: function() {
                        // Hide loading and restore button
                        showLoading(false);
                        $('#searchBtn').prop('disabled', false);
                        $('#searchBtnText').text('Pesquisar');
                    }
                });
            }

            // Show/Hide Loading
            function showLoading(show) {
                if (show) {
                    $('#loadingOverlay').removeClass('d-none');
                } else {
                    $('#loadingOverlay').addClass('d-none');
                }
            }

            // Show Results
            function showResults() {
                $('#resultsContainer').show();
                $('#noResultsContainer').hide();
            }

            // Show No Results
            function showNoResults() {
                $('#resultsContainer').hide();
                $('#noResultsContainer').show();
            }

            // Hide Results
            function hideResults() {
                $('#resultsContainer').hide();
                $('#noResultsContainer').hide();
            }

            // Populate Results Table
            function populateResults(results, total) {
                const tbody = $('#resultsTableBody');
                tbody.empty();
                
                $('#resultsTitle').text(`Resultados Encontrados (${total.toLocaleString()})`);
                
                const situacoes = {
                    '01': ['Nula', 'danger'],
                    '02': ['Ativa', 'success'],
                    '03': ['Suspensa', 'warning'],
                    '04': ['Inapta', 'secondary'],
                    '08': ['Baixada', 'dark']
                };
                
                $.each(results, function(index, estabelecimento) {
                    const cnpj = estabelecimento.cnpj_completo || (estabelecimento.cnpj_basico + (estabelecimento.cnpj_ordem || '') + (estabelecimento.cnpj_dv || ''));
                    
                    const razaoSocial = (estabelecimento.empresa && estabelecimento.empresa.razao_social) || 'N/A';
                    const nomeFantasia = estabelecimento.nome_fantasia || '-';
                    
                    const situacaoInfo = situacoes[estabelecimento.situacao_cadastral] || ['Desconhecida', 'light'];
                    const situacaoBadge = `<span class="badge bg-${situacaoInfo[1]}">${situacaoInfo[0]}</span>`;
                    
                    const cnaePrincipal = estabelecimento.cnae_principal || '-';
                    const uf = estabelecimento.uf || '-';
                    const municipio = estabelecimento.municipio || '-';
                    
                    let capitalSocial = '-';
                    if (estabelecimento.empresa && estabelecimento.empresa.capital_social) {
                        const valor = parseFloat(estabelecimento.empresa.capital_social);
                        capitalSocial = 'R$ ' + valor.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                    
                    const row = `
                        <tr>
                            <td><code>${cnpj}</code></td>
                            <td>${razaoSocial}</td>
                            <td>${nomeFantasia}</td>
                            <td>${situacaoBadge}</td>
                            <td>${cnaePrincipal}</td>
                            <td>${uf}</td>
                            <td>${municipio}</td>
                            <td>${capitalSocial}</td>
                        </tr>
                    `;
                    
                    tbody.append(row);
                });
            }

            // Clear Form
            function clearForm() {
                $('#searchForm')[0].reset();
                $('.select2').val(null).trigger('change');
                $('#municipio').empty()
                    .append('<option value="">Selecione um estado primeiro...</option>')
                    .prop('disabled', true);
                $('#capital_entre').val('');
                $('#capital_inicial').val('');
                $('#capital_final').val('');
                hideResults();
                clearAlerts();
            }

            // Show Alert
            function showAlert(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').html(alertHtml);
            }

            // Clear Alerts
            function clearAlerts() {
                $('#alertContainer').empty();
            }
        });
    </script>
</body>
</html>