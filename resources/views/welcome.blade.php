<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EWAS</title>
    <link rel="stylesheet" type="text/css" href="http://unpkg.com/iview/dist/styles/iview.css">
    <script type="text/javascript" src="http://vuejs.org/js/vue.js"></script>
    <script type="text/javascript" src="http://unpkg.com/iview/dist/iview.min.js"></script>
    <script src="//unpkg.com/iview/dist/locale/en-US.js"></script>

    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div id="app">
    <form>
        <fieldset>
            <legend>Genetic Location</legend>
            Chr: <select id="chr_combo" v-model="chr">
                <option value="0" selected="selected">Any</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">X</option>
            </select>
            <select style="margin-left: 15px" v-model="gene_or_position">
                <option value="0" selected="selected">Gene</option>
                <option value="1">Position</option>
            </select>
            <input v-model="gene_text" type="text">
        </fieldset>
        <fieldset>
            <legend>Limit Results to</legend>
            p-value:<select v-model="pval">
                <option value="0" selected="selected">Any</option>
                <option value="1">&lt; 1e-10</option>
                <option value="2">&lt; 1e-9</option>
                <option value="3">&lt; 1e-8</option>
                <option value="4">&lt; 1e-7</option>
                <option value="5">&lt; 1e-6</option>
                <option value="6">&lt; 1e-5</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>Phenotype</legend>
            Disease/Trait:
            <i-select v-model="traits" style="width:200px">
                @foreach ($trait as $t)
                    <i-option value="{{$t->Trait}}">{{$t->Trait}}</i-option>
                @endforeach
            </i-select>
            {{--<input type="text" @focus="focusTrait" v-model="traits" width="300px">--}}
            {{--<Modal v-model="trait_visible" title="Select Disease/Trait" :mask-closable="false">--}}
                {{--<checkbox-group v-model="traits">--}}
                    {{--@foreach ($trait as $t)--}}
                        {{--<Checkbox label="{{$t->Trait}}">{{$t->Trait}}</Checkbox>--}}
                    {{--@endforeach--}}
                {{--</checkbox-group>--}}
            {{--</Modal>--}}

            <span style="margin-left: 30px">Tissue: </span>
            <i-select v-model="tissues" style="width:200px">
                @foreach ($tissue as $t)
                    <i-option value="{{$t->Tissue}}">{{$t->Tissue}}</i-option>
                @endforeach
            </i-select>
            {{--<input type="text" @focus="focusTissue" v-model="tissues">--}}
            {{--<Modal v-model="tissue_visible" title="Select Tissue" :mask-closable="false">--}}
                {{--<checkbox-group v-model="tissues">--}}
                    {{--@foreach ($tissue as $t)--}}
                        {{--<Checkbox label="{{$t->Tissue}}">{{$t->Tissue}}</Checkbox>--}}
                    {{--@endforeach--}}
                {{--</checkbox-group>--}}
            {{--</Modal>--}}

        </fieldset>
    </form>

    <div id="med_part" style="margin-top: 10px">

        <i-button shape="circle" icon="ios-search" @click="search">Search</i-button>
        <i-button shape="circle" @click="reset">Reset</i-button>

        <span id="results_summary" class="current_counter">All <span class="counter_number">@{{ total }}</span> results are selected</span>
        <span id="cur_query" class="current_query">All chromosomes, all genes, All p-values, All Phenotypes</span>
        <i-button v-if="download_visible" shape="circle" @click="download" icon="md-download">Download</i-button>

        <Page v-if="page_visible" :current=page :total=total class-name="page_input" simple
              @on-change="pageChanged"></Page>

    </div>
    {{--<span>Selected: @{{ pval }}</span>--}}
    <br>
    <hr>
    <br>
    <i-table stripe border size="small" :loading="loading" :columns="columns" :data="tableData"></i-table>
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="js/main.js"></script>
<script>
    iview.lang('en-US');
    var app = new Vue({
        el: '#app',
        data: {
            loading: false,
            chr: '0',
            gene_or_position: '0',
            gene_text: '',
            pval: '0',
            page: 1,
            total: 30309,
            page_visible: false,
            download_visible: false,

            tissue_visible: false,
            tissues: [],

            traits: [],
            trait_visible: false,

            columns: [
                {
                    title: 'cpg_ID',
                    key: 'cpg_ID'
                },
                {
                    title: 'Trait',
                    key: 'Trait',
                    width: 200
                },
                {
                    title: 'PMID',
                    key: 'PMID',
                    // width: 150,
                    render: (h, params) => {
                        if (params.row.PMID.length > 0) {
                            return h('a', {
                                attrs: {
                                    href: 'https://www.ncbi.nlm.nih.gov/pubmed/?term=' + params.row.PMID,
                                    target: '_blank'
                                }
                            }, params.row.PMID);
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    title: 'GEO_ID',
                    key: 'GEO_ID',
                    render: (h, params) => {
                        if (params.row.GEO_ID.length > 0) {
                            return h('a', {
                                attrs: {
                                    href: 'https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=' + params.row.GEO_ID,
                                    target: '_blank'
                                }
                            }, params.row.GEO_ID);
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    title: 'chr',
                    key: 'chr',
                    width: 60
                },
                {
                    title: 'position',
                    key: 'position'
                },
                {
                    title: 'Tissue',
                    key: 'Tissue'
                },
                {
                    title: 'p_value',
                    key: 'p_value'
                },
                {
                    title: 'Gene',
                    key: 'Gene_name'
                }
            ],
            tableData: []
        },
        methods: {
            search: function () {
                this.loading = true;
                this.page = 1;
                this.total = 0;
                this.page_visible = true;
                console.log(this.traits);
                axios.post('/search', {
                    chr: this.chr,
                    gene_or_position: this.gene_or_position,
                    gene_text: this.gene_text,
                    pval: this.pval,
                    trait: this.traits,
                    tissue: this.tissues
                })
                    .then(function (response) {
                        console.log(response.data);
                        app.tableData = response.data.data;
                        app.total = response.data.count;
                        app.loading = false;
                        if (app.total > 0) {
                            app.download_visible = true;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            pageChanged: function (page) {
                // console.log(page)
                this.loading = true;
                this.page_visible = true;

                axios.post('/search', {
                    chr: this.chr,
                    gene_or_position: this.gene_or_position,
                    gene_text: this.gene_text,
                    pval: this.pval,
                    trait: this.traits,
                    tissue: this.tissues,
                    page: page
                })
                    .then(function (response) {
                        console.log(response.data);
                        app.tableData = response.data.data;
                        app.total = response.data.count;
                        app.loading = false;
                        if (app.total > 0) {
                            app.download_visible = true;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            download: function () {

            },

            focusTrait: function () {
                this.trait_visible = true;
            },

            focusTissue: function () {
                this.tissue_visible = true;
            },

            reset: function () {
                this.tableData = [];
                this.chr = '0';
                this.gene_or_position = '0';
                this.gene_text = '';
                this.pval = '0';
                this.trait = '';
                this.page = 0;
                this.total = 30309;
                this.loading = false;
                this.page_visible = false;
                this.download_visible = false;

                // this.tissues = [];
                // this.traits = [];
                this.tissues = '';
                this.traits = '';
            }
        }
    })

</script>
</body>
</html>
