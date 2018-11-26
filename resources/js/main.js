iview.lang('en-US');
var app = new Vue({
    el: '#app',
    data: {
        loading: false,
        chr: '0',
        gene_or_position: '0',
        gene_text: '',
        pval: '0',
        trait: '',
        page: 1,
        total: 30309,
        page_visible: false,
        download_visible: false,
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
                width: 100
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
        search: function () {
            this.loading = true;
            this.page = 1;
            this.total = 0;
            this.page_visible = true;

            axios.post('/search', {
                chr: this.chr,
                gene_or_position: this.gene_or_position,
                gene_text: this.gene_text,
                pval: this.pval,
                trait: this.trait
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
                trait: this.trait,
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
        }
    }
})
