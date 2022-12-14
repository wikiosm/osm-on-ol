/* Copyright (c) 2006-2008 MetaCarta, Inc., published under the Clear BSD
 * license.  See http://svn.openlayers.org/trunk/openlayers/license.txt for the
 * full text of the license. */

/* Translators (2009 onwards):
 *  - Minh Nguyen
 */

/**
 * @requires OpenLayers/Lang.js
 */

/**
 * Namespace: OpenLayers.Lang["vi"]
 * Dictionary for Tiếng Việt.  Keys for entries are used in calls to
 *     <OpenLayers.Lang.translate>.  Entry bodies are normal strings or
 *     strings formatted for use with <OpenLayers.String.format> calls.
 */
OpenLayers.Lang["vi"] = OpenLayers.Util.applyDefaults({

    'unhandledRequest': "Không xử lý được phản hồi ${statusText} cho yêu cầu",

    'permalink': "Liên kết thường trực",

    'Overlays': "Lấp bản đồ",

    'Base Layer': "Lớp nền",

    'sameProjection': "Bản đồ toàn cảnh chỉ hoạt động khi cùng phép chiếu với bản đồ chính",

    'readNotImplemented': "Chưa hỗ trợ chức năng đọc.",

    'writeNotImplemented': "Chưa hỗ trợ chức năng viết.",

    'noFID': "Không thể cập nhật tính năng thiếu FID.",

    'errorLoadingGML': "Lỗi tải tập tin GML tại ${url}",

    'browserNotSupported': "Trình duyệt của bạn không hỗ trợ chức năng vẽ bằng vectơ. Hiện hỗ trợ các bộ kết xuất:\n${renderers}",

    'componentShouldBe': "addFeatures: bộ phận cần phải là ${geomType}",

    'getFeatureError': "getFeatureFromEvent được gọi từ lớp không có bộ kết xuất. Thường thì có lẽ lớp bị xóa nhưng một phần xử lý của nó vẫn còn.",

    'minZoomLevelError': "Chỉ nên sử dụng thuộc tính minZoomLevel với các lớp FixedZoomLevels-descendent. Việc lớp wfs này tìm cho minZoomLevel là di tích còn lại từ xưa. Tuy nhiên, nếu chúng tôi dời nó thì sẽ vỡ các chương trình OpenLayers mà dựa trên nó. Bởi vậy chúng tôi phản đối sử dụng nó\x26nbsp;– bước tìm cho minZoomLevel sẽ được dời vào phiên bản 3.0. Xin sử dụng thiết lập độ phân tích tối thiểu / tối đa thay thế, theo hướng dẫn này: http://trac.openlayers.org/wiki/SettingZoomLevels",

    'commitSuccess': "Giao dịch WFS: THÀNH CÔNG ${response}",

    'commitFailed': "Giao dịch WFS: THẤT BẠI ${response}",

    'googleWarning': "Không thể tải lớp Google đúng đắn.\x3cbr\x3e\x3cbr\x3eĐể tránh thông báo này lần sau, hãy chọn BaseLayer mới dùng điều khiển chọn lớp ở góc trên phải.\x3cbr\x3e\x3cbr\x3eChắc script thư viện Google Maps hoặc không được bao gồm hoặc không chứa khóa API hợp với website của bạn.\x3cbr\x3e\x3cbr\x3e\x3ca href=\'http://trac.openlayers.org/wiki/Google\' target=\'_blank\'\x3eTrợ giúp về tính năng này\x3c/a\x3e cho người phát triển.",

    'getLayerWarning': "Không thể tải lớp ${layerType} đúng đắn.\x3cbr\x3e\x3cbr\x3eĐể tránh thông báo này lần sau, hãy chọn BaseLayer mới dùng điều khiển chọn lớp ở góc trên phải.\x3cbr\x3e\x3cbr\x3eChắc script thư viện ${layerLib} không được bao gồm đúng kiểu.\x3cbr\x3e\x3cbr\x3e\x3ca href=\'http://trac.openlayers.org/wiki/${layerLib}\' target=\'_blank\'\x3eTrợ giúp về tính năng này\x3c/a\x3e cho người phát triển.",

    'scale': "Tỷ lệ = 1 : ${scaleDenom}",

    'W': "T",

    'E': "Đ",

    'N': "B",

    'S': "N",

    'layerAlreadyAdded': "Bạn muốn thêm lớp ${layerName} vào bản đồ, nhưng lớp này đã được thêm",

    'reprojectDeprecated': "Bạn đang áp dụng chế độ “reproject” vào lớp ${layerName}. Chế độ này đã bị phản đối: nó có mục đích hỗ trợ lấp dữ liệu trên các nền bản đồ thương mại; nên thực hiện hiệu ứng đó dùng tính năng Mercator Hình cầu. Có sẵn thêm chi tiết tại http://trac.openlayers.org/wiki/SphericalMercator .",

    'methodDeprecated': "Phương thức này đã bị phản đối và sẽ bị dời vào phiên bản 3.0. Xin hãy sử dụng ${newMethod} thay thế.",

    'boundsAddError': "Cần phải cho cả giá trị x và y vào hàm add.",

    'lonlatAddError': "Cần phải cho cả giá trị lon và lat vào hàm add.",

    'pixelAddError': "Cần phải cho cả giá trị x và y vào hàm add.",

    'unsupportedGeometryType': "Không hỗ trợ kiểu địa lý: ${geomType}",

    'pagePositionFailed': "OpenLayers.Util.pagePosition bị thất bại: nguyên tố với ID ${elemId} có thể ở chỗ sai.",

    'filterEvaluateNotImplemented': "chưa hỗ trợ evaluate cho loại bộ lọc này."

});