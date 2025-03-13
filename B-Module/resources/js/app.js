/**
 * 비회원 주문 시스템 JavaScript
 */
class NonMemberOrderSystem {
  constructor() {
    // DOM 요소
    this.$modal = $("#nonMemberOrderModal");
    this.$orderArea = $("#orderArea");
    this.$orderList = $("#orderProductList");
    this.$emptyCart = $(".empty-cart");
    this.$removeMsg = $(".remove-instruction");
    this.$totalAmount = $("#totalProductAmount");
    this.$shipping = $("#shippingFee");
    this.$totalPayment = $("#totalPaymentAmount");
    this.$orderBtn = $("#orderButton");
    
    // 데이터
    this.products = [];
    this.guestId = this.generateId(6);
    
    this.init();
  }
  
  // 랜덤 ID 생성
  generateId(length) {
    return Math.random().toString(36).substring(2, 2 + length).toUpperCase();
  }
  
  // 초기화
  init() {
    $("#guestId").text(this.guestId);
    
    // 상품 데이터 로드
    $.getJSON("./resources/js/data.json", data => {
      this.products = data.data;
      this.renderProducts();
      this.initTabs();
    });
    
    this.bindEvents();
  }
  
  // 상품 렌더링
  renderProducts() {
    // 카테고리별 상품 그룹화
    const groups = {};
    this.products.forEach(product => {
      if (!groups[product.category]) groups[product.category] = [];
      groups[product.category].push(product);
    });
    
    // 각 카테고리 상품 추가
    Object.keys(groups).forEach(category => {
      const $container = $(`#display-${category}`).empty();
      
      groups[category].forEach(product => {
        // 할인가 계산
        let price = product.price;
        let oldPrice = '';
        let discount = '';
        
        if (product.discountOption === 'percent' && product.discountValue > 0) {
          price = Math.round(price * (1 - product.discountValue));
          oldPrice = `<span class="old-price">${product.price.toLocaleString()}원</span>`;
          discount = `<div class="discount-badge">-${product.discountValue * 100}%</div>`;
        } else if (product.discountOption === 'minus' && product.discountValue > 0) {
          price = price - product.discountValue;
          oldPrice = `<span class="old-price">${product.price.toLocaleString()}원</span>`;
          discount = `<div class="discount-badge">-${product.discountValue.toLocaleString()}원</div>`;
        }
        
        // 이미지 경로 수정
        const imgPath = product.image.replace(/^\//, './resources/');
        
        // 상품 카드 추가
        $container.append(`
          <div class="product-card" draggable="true" data-id="${product.id}">
            <div class="product-thumb">
              <img src="${imgPath}" alt="${product.title}">
            </div>
            <div class="product-info">
              <h4 class="product-title">${product.title}</h4>
              <div class="product-price">
                ${oldPrice}
                <span class="current-price">${price.toLocaleString()}원</span>
              </div>
              ${discount}
            </div>
          </div>
        `);
      });
    });
  }
  
  // 탭 초기화
  initTabs() {
    $("#nonMemberOrderModal .tab-item:first").addClass("active");
    $("#display-health").addClass("active");
    
    $("#nonMemberOrderModal .tab-item").on("click", function() {
      const category = $(this).data("category");
      $("#nonMemberOrderModal .tab-item").removeClass("active");
      $(this).addClass("active");
      $("#nonMemberOrderModal .product-category").removeClass("active");
      $(`#display-${category}`).addClass("active");
    });
  }
  
  // 이벤트 바인딩
  bindEvents() {
    // 모달 열기/닫기
    $("#nonMemberOrderBtn").on("click", () => this.$modal.css("display", "block"));
    $(".close-modal").on("click", () => this.$modal.css("display", "none"));
    $(".modal-overlay").on("click", e => {
      if (e.target === e.currentTarget) this.$modal.css("display", "none");
    });
    
    // 주문 버튼
    this.$orderBtn.on("click", () => {
      if (this.$orderList.children().length > 0) this.processOrder();
    });
    
    // 드래그 앤 드롭
    this.setupDragDrop();
    
    // 수량 변경 버튼
    $(document).on("click", ".decrease-quantity", e => {
      const $item = $(e.target).closest(".order-item");
      const $input = $item.find(".quantity-input");
      const qty = parseInt($input.val());
      
      if (qty > 1) {
        $input.val(qty - 1).trigger("change");
      } else {
        this.removeOrderItem($item.data("id"));
      }
    });
    
    $(document).on("click", ".increase-quantity", e => {
      const $input = $(e.target).closest(".order-item").find(".quantity-input");
      $input.val(parseInt($input.val()) + 1).trigger("change");
    });
    
    // 수량 변경 시 가격 업데이트
    $(document).on("change", ".quantity-input", e => {
      const $item = $(e.target).closest(".order-item");
      const id = $item.data("id");
      const qty = parseInt($(e.target).val());
      if (qty < 1) {
        $(e.target).val(1);
        return;
      }
      
      const product = this.getProduct(id);
      if (!product) return;
      
      // 최종 가격 계산
      let price = product.price;
      if (product.discountOption === 'percent') {
        price = Math.round(price * (1 - product.discountValue));
      } else if (product.discountOption === 'minus') {
        price = price - product.discountValue;
      }
      
      // 금액 업데이트
      const total = price * qty;
      $item.find(".total-price").text(`${total.toLocaleString()}원`);
      
      this.updateTotals();
    });
  }
  
  // 상품 정보 조회
  getProduct(id) {
    return this.products.find(p => p.id == id);
  }
  
  // 드래그 앤 드롭 설정
  setupDragDrop() {
    // 상품 드래그 시작 - ID 선택자로 범위 제한하여 충돌 방지
    $(document).on("dragstart", "#nonMemberOrderModal .product-card", function(e) {
      const id = $(this).data("id");
      e.originalEvent.dataTransfer.setData("productId", id);
      e.originalEvent.dataTransfer.setData("type", "product");
    });
    
    // 주문 항목 드래그 시작
    $(document).on("dragstart", ".order-item", function(e) {
      const id = $(this).data("id");
      e.originalEvent.dataTransfer.setData("productId", id);
      e.originalEvent.dataTransfer.setData("type", "order");
      $(this).addClass("dragging");
    });
    
    // 드래그 종료
    $(document).on("dragend", ".order-item", function() {
      $(this).removeClass("dragging");
    });
    
    // 주문 영역 드래그 오버/리브
    this.$orderArea.on("dragover", e => {
      e.preventDefault();
      this.$orderArea.addClass("drag-over");
    }).on("dragleave", () => {
      this.$orderArea.removeClass("drag-over");
    });
    
    // 주문 영역 드롭
    this.$orderArea.on("drop", e => {
      e.preventDefault();
      this.$orderArea.removeClass("drag-over");
      
      const id = e.originalEvent.dataTransfer.getData("productId");
      const type = e.originalEvent.dataTransfer.getData("type");
      
      // 주문 항목을 주문 영역에 드롭하면 무시
      if (type === "order") return;
      
      const product = this.getProduct(id);
      if (!product) return;
      
      // 이미 있는 상품인지 확인
      const $existing = this.$orderList.find(`.order-item[data-id="${id}"]`);
      
      if ($existing.length > 0) {
        // 수량 증가
        const $input = $existing.find(".quantity-input");
        $input.val(parseInt($input.val()) + 1).trigger("change");
      } else {
        // 새 주문 항목 추가
        this.addOrderItem(product);
      }
    });
    
    // 모달 영역에 드롭 - 주문 항목 제거
    $("body").on("dragover", e => {
      e.preventDefault();
    }).on("drop", e => {
      e.preventDefault();
      
      // 주문 영역에 들어있지 않은 곳에 드롭할 경우
      if (!$(e.target).closest("#orderArea").length) {
        const id = e.originalEvent.dataTransfer.getData("productId");
        const type = e.originalEvent.dataTransfer.getData("type");
        
        // 주문 항목만 삭제 가능
        if (type === "order") {
          this.removeOrderItem(id);
        }
      }
    });
  }
  
  // 주문 항목 추가
  addOrderItem(product) {
    // 최종 가격 계산
    let price = product.price;
    if (product.discountOption === 'percent') {
      price = Math.round(price * (1 - product.discountValue));
    } else if (product.discountOption === 'minus') {
      price = price - product.discountValue;
    }
    
    // 이미지 경로 수정
    const imgPath = product.image.replace(/^\//, './resources/');
    
    // 주문 항목 추가
    this.$orderList.append(`
      <div class="order-item" draggable="true" data-id="${product.id}">
        <div class="order-item-image">
          <img src="${imgPath}" alt="${product.title}">
        </div>
        <div class="order-item-info">
          <h4 class="order-item-title">${product.title}</h4>
          <div class="order-item-price">
            <span class="price">${price.toLocaleString()}원</span>
          </div>
        </div>
        <div class="order-item-quantity">
          <button class="decrease-quantity"><i class="fa fa-minus"></i></button>
          <input type="number" min="1" value="1" class="quantity-input">
          <button class="increase-quantity"><i class="fa fa-plus"></i></button>
        </div>
        <div class="order-item-total">
          <span class="total-price">${price.toLocaleString()}원</span>
        </div>
      </div>
    `);
    
    // UI 업데이트
    this.$emptyCart.hide();
    this.$removeMsg.show();
    this.$orderBtn.prop("disabled", false);
    
    this.updateTotals();
  }
  
  // 주문 항목 제거
  removeOrderItem(id) {
    this.$orderList.find(`.order-item[data-id="${id}"]`).remove();
    
    if (this.$orderList.children().length === 0) {
      this.$emptyCart.show();
      this.$removeMsg.hide();
      this.$orderBtn.prop("disabled", true);
    }
    
    this.updateTotals();
  }
  
  // 주문 금액 업데이트
  updateTotals() {
    let totalAmount = 0;
    let shippingFee = 0;
    
    this.$orderList.find(".order-item").each((i, item) => {
      // 각 항목의 가격 합산
      const priceText = $(item).find(".total-price").text();
      totalAmount += parseInt(priceText.replace(/[^\d]/g, ""));
      
      // 첫 항목의 배송비 사용
      if (i === 0) {
        const id = $(item).data("id");
        const product = this.getProduct(id);
        shippingFee = product ? product.shipPrice : 0;
      }
    });
    
    // UI 업데이트
    this.$totalAmount.text(`${totalAmount.toLocaleString()}원`);
    this.$shipping.text(`${shippingFee.toLocaleString()}원`);
    this.$totalPayment.text(`${(totalAmount + shippingFee).toLocaleString()}원`);
  }
  
  // 주문 처리
  processOrder() {
    // 주문 완료 알림 표시
    $("#notificationMessage").text(`주문이 완료되었습니다. 주문번호: ${this.guestId}`);
    $("#orderCompleteNotification").css("display", "block");
    
    // 3초 후 알림 숨김
    setTimeout(() => {
      $("#orderCompleteNotification").css("display", "none");
    }, 3000);
    
    // 주문 초기화
    this.$orderList.empty();
    this.$emptyCart.show();
    this.$removeMsg.hide();
    this.updateTotals();
    this.$orderBtn.prop("disabled", true);
    
    // 모달 닫기
    this.$modal.css("display", "none");
    
    // 새 ID 생성
    this.guestId = this.generateId(6);
    $("#guestId").text(this.guestId);
  }
}

$(document).ready(() => {
  new NonMemberOrderSystem;
})