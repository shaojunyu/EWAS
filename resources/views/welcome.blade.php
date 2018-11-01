<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EWAS</title>
    <link rel="stylesheet" type="text/css" href="http://unpkg.com/iview/dist/styles/iview.css">
    <script type="text/javascript" src="http://vuejs.org/js/vue.min.js"></script>
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
            <input v-model="trait" type="text">
        </fieldset>
    </form>

    <div id="med_part" style="margin-top: 10px">

        <i-button shape="circle" icon="ios-search" @click="show">Search</i-button>
        <i-button shape="circle">Reset</i-button>

        <span id="results_summary" class="current_counter">All <span class="counter_number">30032</span> results are selected</span>
        <span id="cur_query" class="current_query">All chromosomes, all genes, All p-values, All Sources, All Functional classes, All Phenotypes</span>
        <!-- <span id="cur_sel_display" class="current_query" ></span> -->

        <Page :current="2" :total=500 simple></Page>
    </div>
    {{--<span>Selected: @{{ pval }}</span>--}}
    <br>
    <hr>

    <br>


    <i-table stripe border size="small" :columns="columns" :data="tableData"></i-table>
    {{--<template>--}}
    {{--<Table :columns="columns1" :data="data1"></Table>--}}

    {{--</template>--}}
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    iview.lang('en-US');
    var app = new Vue({
        el: '#app',
        data: {
            chr: 0,
            gene_or_position: 0,
            gene_text: '',
            pval: 0,
            trait: '',
            page:0,
            total_page:0,
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
                    width:100
                },
                {
                    title: 'GEO_ID',
                    key: 'GEO_ID'
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
            show: function () {
                // this.table_data = [{"cpg_ID": "cg20012308", "Trait": "Rheumatoid Arthritis",
                //     "PMID": "23334450", "GEO_ID": "GSE42861", "chr": "1"}];

                axios.post('/search', {
                    chr: this.chr,
                    gene_or_position: this.gene_or_position,
                    gene_text: this.gene_text,
                    pval: this.pval,
                    trait: this.trait
                })
                    .then(function (response) {
                        console.log(response.data);
                        app.tableData = [{
                            "cpg_ID": "cg20012308", "Trait": "Rheumatoid Arthritis",
                            "PMID": "23334450", "GEO_ID": "GSE42861", "chr": "1"
                        }];
                        app.tableData = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    })
</script>
</body>
</html>
