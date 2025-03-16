/**
 * 비회원 주문 시스템 클래스
 * 상품 목록 표시, 장바구니 기능, 주문 처리 등을 담당
 */
class NonMemberOrderSystem {
    constructor() {
      // DOM 요소 선택 및 저장 (성능 최적화를 위해 캐싱)
      this.$modal = $("#nonMemberOrderModal");        // 주문 모달창
      this.$orderArea = $("#orderArea");              // 주문 영역 컨테이너
      this.$orderList = $("#orderProductList");       // 주문 상품 목록
      this.$emptyCart = $(".empty-cart");             // 빈 장바구니 메시지
      this.$removeMsg = $(".remove-instruction");     // 삭제 안내 메시지
      this.$totalAmount = $("#totalProductAmount");   // 총 상품 금액
      this.$shipping = $("#shippingFee");             // 배송비
      this.$totalPayment = $("#totalPaymentAmount");  // 최종 결제 금액
      this.$orderBtn = $("#orderButton");             // 주문하기 버튼
      
      // 데이터 변수
      this.products = [];                             // 상품 데이터 배열
      this.guestId = this.generateId(6);              // 6자리 랜덤 ID 생성
      
      // 초기화 함수 호출
      this.init();
    }
    
    /**
     * 랜덤 ID 생성 (영문 대문자 + 숫자)
     * @param {number} length - ID 길이
     * @return {string} 생성된 랜덤 ID
     */
    generateId(length) {
      // Math.random()을 base-36 문자열로 변환 후 대문자화
      return Math.random().toString(36).substring(2, 2 + length).toUpperCase();
    }
    
    /**
     * 초기화 - 페이지 로드시 실행됨
     */
    init() {
      // 게스트 ID 표시
      $("#guestId").text(this.guestId);
      
      // 상품 데이터 로드 (Ajax 요청)
      $.getJSON("./resources/js/data.json", data => {
        this.products = data.data;             // 상품 데이터 저장
        this.renderProducts();                 // 상품 목록 렌더링
        this.initTabs();                       // 카테고리 탭 초기화
      });
      
      // 이벤트 리스너 등록
      this.bindEvents();
    }
    
    /**
     * 상품 목록 렌더링 - 카테고리별로 상품 표시
     */
    renderProducts() {
      // 카테고리별 상품 그룹화
      const groups = {};
      this.products.forEach(product => {
        if (!groups[product.category]) groups[product.category] = [];
        groups[product.category].push(product);
      });
      
      // 각 카테고리별로 상품 렌더링
      Object.keys(groups).forEach(category => {
        // 카테고리 컨테이너 찾고 내용 비우기
        const $container = $(`#display-${category}`).empty();
        
        // 카테고리 내 각 상품 렌더링
        groups[category].forEach(product => {
          // 할인가 계산 및 표시 요소 생성
          let price = product.price;            // 기본 가격
          let oldPrice = '';                    // 원가 표시 (할인 시)
          let discount = '';                    // 할인율/액 표시
          
          // 퍼센트 할인인 경우
          if (product.discountOption === 'percent' && product.discountValue > 0) {
            price = Math.round(price * (1 - product.discountValue));  // 할인가 계산
            oldPrice = `<span class="old-price">${product.price.toLocaleString()}원</span>`;
            discount = `<div class="discount-badge">-${product.discountValue * 100}%</div>`;
          } 
          // 금액 할인인 경우
          else if (product.discountOption === 'minus' && product.discountValue > 0) {
            price = price - product.discountValue;  // 할인가 계산
            oldPrice = `<span class="old-price">${product.price.toLocaleString()}원</span>`;
            discount = `<div class="discount-badge">-${product.discountValue.toLocaleString()}원</div>`;
          }
          
          // 이미지 경로 수정 (상대 경로로 변환)
          const imgPath = product.image.replace(/^\//, './resources/');
          
          // 상품 카드 HTML 생성 및 추가
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
    
    /**
     * 카테고리 탭 초기화 및 이벤트 리스너 설정
     */
    initTabs() {
      // 첫 번째 탭을 기본으로 활성화
      $(".tab-item:first").addClass("active");
      
      // 모달 내부의 카테고리 클래스명 수정
      $(".modal-product-category:first").addClass("active");
      
      // 탭 클릭 이벤트 처리
      $(".tab-item").on("click", function() {
        const category = $(this).data("category");  // 클릭한 탭의 카테고리 값
        
        // 활성 탭 변경
        $(".tab-item").removeClass("active");
        $(this).addClass("active");
        
        // 카테고리 표시 변경 - 모달 내부 클래스명 수정
        $(".modal-product-category").removeClass("active");
        $(`#display-${category}`).addClass("active");
      });
    }
    
    /**
     * 이벤트 리스너 등록
     */
    bindEvents() {
      // 모달 열기 - 비회원 주문 버튼 클릭
      $("#nonMemberOrderBtn").on("click", () => this.$modal.css("display", "block"));
      
      // 모달 닫기 - X 버튼 클릭
      $(".close-modal").on("click", () => this.$modal.css("display", "none"));
      
      // 모달 닫기 - 모달 바깥 영역 클릭
      $(".modal-overlay").on("click", e => {
        if (e.target === e.currentTarget) this.$modal.css("display", "none");
      });
      
      // 주문하기 버튼 클릭
      this.$orderBtn.on("click", () => {
        if (this.$orderList.children().length > 0) this.processOrder();
      });
      
      // 드래그 앤 드롭 이벤트 설정
      this.setupDragDrop();
      
      // 수량 감소 버튼 클릭
      $(document).on("click", ".decrease-quantity", e => {
        const $item = $(e.target).closest(".order-item");  // 버튼이 속한 주문 항목
        const $input = $item.find(".quantity-input");      // 수량 입력 필드
        const qty = parseInt($input.val());                // 현재 수량
        
        if (qty > 1) {
          // 수량이 1 초과면 감소
          $input.val(qty - 1).trigger("change");  // change 이벤트 트리거
        } else {
          // 수량이 1이면 항목 제거
          this.removeOrderItem($item.data("id"));
        }
      });
      
      // 수량 증가 버튼 클릭
      $(document).on("click", ".increase-quantity", e => {
        const $input = $(e.target).closest(".order-item").find(".quantity-input");
        $input.val(parseInt($input.val()) + 1).trigger("change");  // 수량 증가 후 change 이벤트 트리거
      });
      
      // 수량 변경 시 (직접 입력 포함)
      $(document).on("change", ".quantity-input", e => {
        const $item = $(e.target).closest(".order-item");  // 수량 입력필드가 속한 주문 항목
        const id = $item.data("id");                      // 상품 ID
        let qty = parseInt($(e.target).val());            // 입력된 수량
        
        // 수량이 1 미만이면 1로 조정
        if (qty < 1) {
          $(e.target).val(1);
          qty = 1;
        }
        
        // 상품 정보 가져오기
        const product = this.getProduct(id);
        if (!product) return;
        
        // 최종 가격 계산
        let price = product.price;
        if (product.discountOption === 'percent') {
          price = Math.round(price * (1 - product.discountValue));
        } else if (product.discountOption === 'minus') {
          price = price - product.discountValue;
        }
        
        // 총 금액 업데이트 (단가 * 수량)
        const total = price * qty;
        $item.find(".total-price").text(`${total.toLocaleString()}원`);
        
        // 주문 총액 업데이트
        this.updateTotals();
      });
    }
    
    /**
     * 상품 ID로 상품 정보 가져오기
     * @param {number} id - 상품 ID
     * @return {object|undefined} 상품 정보 객체 또는 undefined
     */
    getProduct(id) {
      return this.products.find(p => p.id == id);
    }
    
    /**
     * 드래그 앤 드롭 기능 설정
     */
    setupDragDrop() {
      // 상품 카드 드래그 시작
      $(document).on("dragstart", ".product-card", function(e) {
        const id = $(this).data("id");  // 상품 ID
        
        // 드래그 데이터 설정 (상품 ID와 타입)
        e.originalEvent.dataTransfer.setData("productId", id);
        e.originalEvent.dataTransfer.setData("type", "product");
      });
      
      // 주문 항목 드래그 시작
      $(document).on("dragstart", ".order-item", function(e) {
        const id = $(this).data("id");  // 상품 ID
        
        // 드래그 데이터 설정 (상품 ID와 타입)
        e.originalEvent.dataTransfer.setData("productId", id);
        e.originalEvent.dataTransfer.setData("type", "order");
        
        // 드래그 중인 요소 스타일 변경
        $(this).addClass("dragging");
      });
      
      // 드래그 종료
      $(document).on("dragend", ".order-item", function() {
        // 드래그 중 스타일 제거
        $(this).removeClass("dragging");
      });
      
      // 주문 영역 드래그 오버/떠남
      this.$orderArea.on("dragover", e => {
        e.preventDefault();  // 기본 이벤트 방지
        this.$orderArea.addClass("drag-over");  // 드래그 오버 스타일 추가
      }).on("dragleave", () => {
        this.$orderArea.removeClass("drag-over");  // 드래그 오버 스타일 제거
      });
      
      // 주문 영역에 드롭 (상품 추가)
      this.$orderArea.on("drop", e => {
        e.preventDefault();  // 기본 이벤트 방지
        this.$orderArea.removeClass("drag-over");  // 드래그 오버 스타일 제거
        
        // 드롭된 데이터 가져오기
        const id = e.originalEvent.dataTransfer.getData("productId");
        const type = e.originalEvent.dataTransfer.getData("type");
        
        // 주문 항목을 주문 영역에 드롭하면 무시 (중복 추가 방지)
        if (type === "order") return;
        
        // 상품 정보 가져오기
        const product = this.getProduct(id);
        if (!product) return;
        
        // 이미 있는 상품인지 확인
        const $existing = this.$orderList.find(`.order-item[data-id="${id}"]`);
        
        if ($existing.length > 0) {
          // 기존 항목이 있으면 수량 증가
          const $input = $existing.find(".quantity-input");
          $input.val(parseInt($input.val()) + 1).trigger("change");
        } else {
          // 새 주문 항목 추가
          this.addOrderItem(product);
        }
      });
      
      // 전체 문서에 드래그 오버 이벤트 등록 (드롭 가능하게)
      $("body").on("dragover", e => {
        e.preventDefault();
      }).on("drop", e => {
        e.preventDefault();
        
        // 주문 영역 외부에 드롭 - 항목 제거
        if (!$(e.target).closest("#orderArea").length) {
          const id = e.originalEvent.dataTransfer.getData("productId");
          const type = e.originalEvent.dataTransfer.getData("type");
          
          // 주문 항목만 제거 가능
          if (type === "order") {
            this.removeOrderItem(id);
          }
        }
      });
    }
    
    /**
     * 주문 항목 추가
     * @param {object} product - 추가할 상품 정보
     */
    addOrderItem(product) {
      // 최종 가격 계산
      let price = product.price;
      if (product.discountOption === 'percent') {
        price = Math.round(price * (1 - product.discountValue));
      } else if (product.discountOption === 'minus') {
        price = price - product.discountValue;
      }
      
      // 이미지 경로 수정 (상대 경로로 변환)
      const imgPath = product.image.replace(/^\//, './resources/');
      
      // 주문 항목 HTML 생성 및 추가
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
      
      // UI 상태 업데이트
      this.$emptyCart.hide();           // 빈 장바구니 메시지 숨김
      this.$removeMsg.show();           // 삭제 안내 메시지 표시
      this.$orderBtn.prop("disabled", false);  // 주문 버튼 활성화
      
      // 주문 총액 업데이트
      this.updateTotals();
    }
    
    /**
     * 주문 항목 제거
     * @param {number} id - 제거할 상품 ID
     */
    removeOrderItem(id) {
      // ID가 일치하는 주문 항목 제거
      this.$orderList.find(`.order-item[data-id="${id}"]`).remove();
      
      // 장바구니가 비었으면 UI 상태 업데이트
      if (this.$orderList.children().length === 0) {
        this.$emptyCart.show();              // 빈 장바구니 메시지 표시
        this.$removeMsg.hide();              // 삭제 안내 메시지 숨김
        this.$orderBtn.prop("disabled", true);  // 주문 버튼 비활성화
      }
      
      // 주문 총액 업데이트
      this.updateTotals();
    }
    
    /**
     * 주문 금액 계산 및 표시 업데이트
     */
    updateTotals() {
      let totalAmount = 0;  // 총 상품 금액
      let shippingFee = 0;  // 배송비
      
      // 각 주문 항목의 금액 합산
      this.$orderList.find(".order-item").each((i, item) => {
        // 항목의 총액에서 숫자만 추출하여 합산
        const priceText = $(item).find(".total-price").text();
        totalAmount += parseInt(priceText.replace(/[^\d]/g, ""));
        
        // 첫 번째 항목의 배송비 사용
        if (i === 0) {
          const id = $(item).data("id");
          const product = this.getProduct(id);
          shippingFee = product ? product.shipPrice : 0;
        }
      });
      
      // UI 금액 업데이트 (천 단위 구분자 적용)
      this.$totalAmount.text(`${totalAmount.toLocaleString()}원`);
      this.$shipping.text(`${shippingFee.toLocaleString()}원`);
      this.$totalPayment.text(`${(totalAmount + shippingFee).toLocaleString()}원`);
    }
    
    /**
     * 주문 처리 - 주문 완료 및 초기화
     */
    processOrder() {
      // 주문 완료 알림 표시
      $("#notificationMessage").text(`주문이 완료되었습니다. 주문번호: ${this.guestId}`);
      $("#orderCompleteNotification").css("display", "block");
      
      // 3초 후 알림 숨김
      setTimeout(() => {
        $("#orderCompleteNotification").css("display", "none");
      }, 3000);
      
      // 주문 상태 초기화
      this.$orderList.empty();           // 주문 목록 비우기
      this.$emptyCart.show();            // 빈 장바구니 메시지 표시
      this.$removeMsg.hide();            // 삭제 안내 메시지 숨김
      this.updateTotals();               // 금액 정보 초기화
      this.$orderBtn.prop("disabled", true);  // 주문 버튼 비활성화
      
      // 모달 닫기
      this.$modal.css("display", "none");
      
      // 새 ID 생성 및 표시
      this.guestId = this.generateId(6);
      $("#guestId").text(this.guestId);
    }
  }
  
  // 문서 로드 완료 시 시스템 초기화
  $(document).ready(() => new NonMemberOrderSystem());