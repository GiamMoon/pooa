document.addEventListener("DOMContentLoaded", function(e) {
  config.colors.borderColor, config.colors.bodyBg, config.colors.headingColor;
  let t = document.querySelector(".datatables-products"),
      s = {
          1: {
              title: "Scheduled",
              class: "bg-label-warning"
          },
          2: {
              title: "Publish",
              class: "bg-label-success"
          },
          3: {
              title: "Inactive",
              class: "bg-label-danger"
          }
      },
      a = {
          0: {
              title: "Household"
          },
          1: {
              title: "Office"
          },
          2: {
              title: "Electronics"
          },
          3: {
              title: "Shoes"
          },
          4: {
              title: "Accessories"
          },
          5: {
              title: "Game"
          }
      },
      r = {
          0: {
              title: "Out_of_Stock"
          },
          1: {
              title: "In_Stock"
          }
      };
  t && new DataTable(t, {
      ajax: assetsPath + "json/ecommerce-product-list.json",
      columns: [
        { data: "id" }, 
        { data: "id", orderable: !1, render: DataTable.render.select()}, 
        { data: "product_name"}, 
        { data: "category"}, 
        { data: "stock"}, 
        { data: "sku"}, 
        { data: "price"}, 
        { data: "quantity"}, 
        { data: "status"}, 
        { data: "id"}],
      columnDefs: [{
          className: "control",
          searchable: !1,
          orderable: !1,
          responsivePriority: 2,
          targets: 0,
          render: function(e, t, n, o) { return "" }
      }, {
          targets: 1,
          orderable: !1,
          searchable: !1,
          responsivePriority: 3,
          checkboxes: !0,
          checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' },
          render: function() { return '<input type="checkbox" class="dt-checkboxes form-check-input">' }
      }, {
          targets: 2,
          responsivePriority: 1,
          render: function(e, t, n, o) {
              var s = n.product_name,
                  a = n.product_brand,
                  r = n.image;
              let c;
              return `
            <div class="d-flex justify-content-start align-items-center product-name">
              <div class="avatar-wrapper">
                <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">${c=r?`<img src="${assetsPath}img/ecommerce-images/${r}" alt="Product-${n.id}" class="rounded">`:`<span class="avatar-initial rounded-2 bg-label-${["success","danger","warning","info","dark","primary","secondary"][Math.floor(6*Math.random())]}">${(a.match(/\b\w/g)||[]).slice(0,2).join("").toUpperCase()}</span>`}</div>
              </div>
              <div class="d-flex flex-column">
                <h6 class="text-nowrap mb-0">${s}</h6>
                <small class="text-truncate d-none d-sm-block">${a}</small>
              </div>
            </div>`
          }
      }, {
          targets: 3,
          responsivePriority: 5,
          render: function(e, t, n, o) {
              n = a[n.category].title;
              return "display" === t ? `
              <span class="text-truncate d-flex align-items-center text-heading">
                ${{Household:`
                <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-warning me-4">
                  <i class="icon-base bx bx-briefcase icon-18px"></i>
                </span>`,Office:`
                <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-info me-4">
                  <i class="icon-base bx bx-home-smile icon-18px"></i>
              </span>`,Electronics:`
              <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-danger me-4">
                <i class="icon-base bx bx-headphone icon-18px"></i>
              </span>`,Shoes:`
              <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-success me-4">
                <i class="icon-base bx bx-walk icon-18px"></i>
              </span>`,Accessories:`
              <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-secondary me-4">
                <i class="icon-base bx bxs-watch icon-18px"></i>
              </span>`,Game:`
              <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-primary me-4">
                <i class="icon-base bx bx-laptop icon-18px"></i>
                </span>`}[n]||""}${n}
              </span>` : n
          }
      }, {
          targets: 4,
          orderable: !1,
          responsivePriority: 3,
          render: function(e, t, n, o) {
              n = n.stock, n = r[n].title;
              return "display" === t ? `
              <span class="text-truncate">
                ${{Out_of_Stock:`
                <label class="switch switch-primary switch-sm">
                <input type="checkbox" class="switch-input" id="switch">
                  <span class="switch-toggle-slider">
                    <span class="switch-off"></span>
                  </span>
                </label>`,In_Stock:`
                <label class="switch switch-primary switch-sm">
                  <input type="checkbox" class="switch-input" checked>
                  <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                  </span>
                </label>`}[n]}
                <span class="d-none">${n}</span>
              </span>` : n
          }
      }, {
          targets: 5,
          render: function(e, t, n, o) {
              return "<span>" + n.sku + "</span>"
          }
      }, {
          targets: 6,
          render: function(e, t, n, o) {
              return "<span>" + n.price + "</span>"
          }
      }, {
          targets: 7,
          responsivePriority: 4,
          render: function(e, t, n, o) {
              return "<span>" + n.qty + "</span>"
          }
      }, {
          targets: -2,
          render: function(e, t, n, o) {
              n = n.status;
              return '<span class="badge ' + s[n].class + '" text-capitalized>' + s[n].title + "</span>"
          }
      }, {
          targets: -1,
          title: "Actions",
          searchable: !1,
          orderable: !1,
          render: function(e, t, n, o) {
              return `
            <div class="d-inline-block text-nowrap">
              <button class="btn btn-icon"><i class="icon-base bx bx-edit icon-md"></i></button>
              <button class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="javascript:void(0);" class="dropdown-item">View</a>
                <a href="javascript:void(0);" class="dropdown-item">Suspend</a>
              </div>
            </div>
          `
          }
      }],
      select: {
          style: "multi",
          selector: "td:nth-child(2)"
      },
      order: [2, "asc"],
      layout: {
          topStart: {
              rowClass: "card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start",
              features: [{
                  search: {
                      className: "me-5 ms-n4 pe-5 mb-n6 mb-md-0",
                      placeholder: "Search Product",
                      text: "_INPUT_"
                  }
              }]
          },
          topEnd: {
              rowClass: "row m-3 my-0 justify-content-between",
              features: [{
                  pageLength: {
                      menu: [10, 25, 50, 100],
                      text: "_MENU_"
                  },
                  buttons: [{
                      extend: "collection",
                      className: "btn btn-label-secondary dropdown-toggle me-4",
                      text: '<span class="d-flex align-items-center gap-2"><i class="icon-base bx bx-export icon-xs"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                      buttons: [{
                          extend: "print",
                          text: '<span class="d-flex align-items-center"><i class="icon-base bx bx-printer me-1"></i>Print</span>',
                          className: "dropdown-item",
                          exportOptions: {
                              columns: [3, 4, 5, 6, 7],
                              format: {
                                  body: function(e, t, n) {
                                      if (e.length <= 0 || !(-1 < e.indexOf("<"))) return e;
                                      {
                                          e = (new DOMParser).parseFromString(e, "text/html");
                                          let t = "";
                                          var o = e.querySelectorAll(".product-name");
                                          return 0 < o.length ? o.forEach(e => {
                                              e = e.querySelector(".fw-medium")?.textContent || e.querySelector(".d-block")?.textContent || e.textContent;
                                              t += e.trim() + " "
                                          }) : t = e.body.textContent || e.body.innerText, t.trim()
                                      }
                                  }
                              }
                          },
                          customize: function(e) {
                              e.document.body.style.color = config.colors.headingColor, e.document.body.style.borderColor = config.colors.borderColor, e.document.body.style.backgroundColor = config.colors.bodyBg;
                              e = e.document.body.querySelector("table");
                              e.classList.add("compact"), e.style.color = "inherit", e.style.borderColor = "inherit", e.style.backgroundColor = "inherit"
                          }
                      }, {
                          extend: "csv",
                          text: '<span class="d-flex align-items-center"><i class="icon-base bx bx-file me-1"></i>Csv</span>',
                          className: "dropdown-item",
                          exportOptions: {
                              columns: [3, 4, 5, 6, 7],
                              format: {
                                  body: function(e, t, n) {
                                      if (e.length <= 0) return e;
                                      e = (new DOMParser).parseFromString(e, "text/html");
                                      let o = "";
                                      var s = e.querySelectorAll(".product-name");
                                      return 0 < s.length ? s.forEach(e => {
                                          e = e.querySelector(".fw-medium")?.textContent || e.querySelector(".d-block")?.textContent || e.textContent;
                                          o += e.trim() + " "
                                      }) : o = e.body.textContent || e.body.innerText, o.trim()
                                  }
                              }
                          }
                      }, {
                          extend: "excel",
                          text: '<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-export me-1"></i>Excel</span>',
                          className: "dropdown-item",
                          exportOptions: {
                              columns: [3, 4, 5, 6, 7],
                              format: {
                                  body: function(e, t, n) {
                                      if (e.length <= 0) return e;
                                      e = (new DOMParser).parseFromString(e, "text/html");
                                      let o = "";
                                      var s = e.querySelectorAll(".product-name");
                                      return 0 < s.length ? s.forEach(e => {
                                          e = e.querySelector(".fw-medium")?.textContent || e.querySelector(".d-block")?.textContent || e.textContent;
                                          o += e.trim() + " "
                                      }) : o = e.body.textContent || e.body.innerText, o.trim()
                                  }
                              }
                          }
                      }, {
                          extend: "pdf",
                          text: '<span class="d-flex align-items-center"><i class="icon-base bx bxs-file-pdf me-1"></i>Pdf</span>',
                          className: "dropdown-item",
                          exportOptions: {
                              columns: [3, 4, 5, 6, 7],
                              format: {
                                  body: function(e, t, n) {
                                      if (e.length <= 0) return e;
                                      e = (new DOMParser).parseFromString(e, "text/html");
                                      let o = "";
                                      var s = e.querySelectorAll(".product-name");
                                      return 0 < s.length ? s.forEach(e => {
                                          e = e.querySelector(".fw-medium")?.textContent || e.querySelector(".d-block")?.textContent || e.textContent;
                                          o += e.trim() + " "
                                      }) : o = e.body.textContent || e.body.innerText, o.trim()
                                  }
                              }
                          }
                      }, {
                          extend: "copy",
                          text: '<i class="icon-base bx bx-copy me-1"></i>Copy',
                          className: "dropdown-item",
                          exportOptions: {
                              columns: [3, 4, 5, 6, 7],
                              format: {
                                  body: function(e, t, n) {
                                      if (e.length <= 0) return e;
                                      e = (new DOMParser).parseFromString(e, "text/html");
                                      let o = "";
                                      var s = e.querySelectorAll(".product-name");
                                      return 0 < s.length ? s.forEach(e => {
                                          e = e.querySelector(".fw-medium")?.textContent || e.querySelector(".d-block")?.textContent || e.textContent;
                                          o += e.trim() + " "
                                      }) : o = e.body.textContent || e.body.innerText, o.trim()
                                  }
                              }
                          }
                      }]
                  }, {
                      text: '<i class="icon-base bx bx-plus me-0 me-sm-1 icon-xs"></i><span class="d-none d-sm-inline-block">Add Product</span>',
                      className: "add-new btn btn-primary",
                      action: function() {
                          window.location.href = "app-ecommerce-product-add.html"
                      }
                  }]
              }]
          },
          bottomStart: {
              rowClass: "row mx-3 justify-content-between",
              features: ["info"]
          },
          bottomEnd: {
              paging: {
                  firstLast: !1
              }
          }
      },
      language: {
          paginate: {
              next: '<i class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-18px"></i>',
              previous: '<i class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-18px"></i>'
          }
      },
      responsive: {
          details: {
              display: DataTable.Responsive.display.modal({
                  header: function(e) {
                      return "Details of " + e.data().product_name
                  }
              }),
              type: "column",
              renderer: function(e, t, n) {
                  var o, s, a, n = n.map(function(e) {
                      return "" !== e.title ? `<tr data-dt-row="${e.rowIndex}" data-dt-column="${e.columnIndex}">
                    <td>${e.title}:</td>
                    <td>${e.data}</td>
                  </tr>` : ""
                  }).join("");
                  return !!n && ((o = document.createElement("div")).classList.add("table-responsive"), s = document.createElement("table"), o.appendChild(s), s.classList.add("table"), (a = document.createElement("tbody")).innerHTML = n, s.appendChild(a), o)
              }
          }
      },
      initComplete: function() {
          var e = this.api();
          e.columns(-2).every(function() {
              let t = this,
                  n = document.createElement("select");
              n.id = "ProductStatus", n.className = "form-select text-capitalize", n.innerHTML = '<option value="">Status</option>', document.querySelector(".product_status").appendChild(n), n.addEventListener("change", function() {
                  var e = n.value ? `^${n.value}$` : "";
                  t.search(e, !0, !1).draw()
              }), t.data().unique().sort().each(function(e) {
                  var t = document.createElement("option");
                  t.value = s[e].title, t.textContent = s[e].title, n.appendChild(t)
              })
          }), e.columns(3).every(function() {
              let t = this,
                  n = document.createElement("select");
              n.id = "ProductCategory", n.className = "form-select text-capitalize", n.innerHTML = '<option value="">Category</option>', document.querySelector(".product_category").appendChild(n), n.addEventListener("change", function() {
                  var e = n.value ? `^${n.value}$` : "";
                  t.search(e, !0, !1).draw()
              }), t.data().unique().sort().each(function(e) {
                  var t = document.createElement("option");
                  t.value = a[e].title, t.textContent = a[e].title, n.appendChild(t)
              })
          }), e.columns(4).every(function() {
              let t = this,
                  n = document.createElement("select");
              n.id = "ProductStock", n.className = "form-select text-capitalize", n.innerHTML = '<option value="">Stock</option>', document.querySelector(".product_stock").appendChild(n), n.addEventListener("change", function() {
                  var e = n.value ? `^${n.value}$` : "";
                  t.search(e, !0, !1).draw()
              }), t.data().unique().sort().each(function(e) {
                  var t = document.createElement("option");
                  t.value = r[e].title, t.textContent = r[e].title, n.appendChild(t)
              })
          })
      }
  }), setTimeout(() => {
      [{
          selector: ".dt-buttons .btn",
          classToRemove: "btn-secondary"
      }, {
          selector: ".dt-search .form-control",
          classToRemove: "form-control-sm",
          classToAdd: "ms-0"
      }, {
          selector: ".dt-search",
          classToAdd: "mb-0 mb-md-6"
      }, {
          selector: ".dt-length .form-select",
          classToRemove: "form-select-sm"
      }, {
          selector: ".dt-length",
          classToAdd: "mb-md-6 mb-4"
      }, {
          selector: ".dt-layout-table",
          classToRemove: "row mt-2"
      }, {
          selector: ".dt-layout-start",
          classToAdd: "mt-0"
      }, {
          selector: ".dt-layout-end",
          classToRemove: "justify-content-between",
          classToAdd: "justify-content-md-between justify-content-center d-flex flex-wrap gap-2 mb-md-0 mb-4 mt-0"
      }, {
          selector: ".dt-layout-full",
          classToRemove: "col-md col-12",
          classToAdd: "table-responsive"
      }].forEach(({
          selector: e,
          classToRemove: n,
          classToAdd: o
      }) => {
          document.querySelectorAll(e).forEach(t => {
              n && n.split(" ").forEach(e => t.classList.remove(e)), o && o.split(" ").forEach(e => t.classList.add(e))
          })
      })
  }, 100)
});