<wsdl:definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tm="http://microsoft.com/wsdl/mime/textMatching/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:tns="http://www.realprofitsolutions.com" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:http="http://schemas.xmlsoap.org/wsdl/http/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://www.realprofitsolutions.com">
<wsdl:types>
<s:schema elementFormDefault="qualified" targetNamespace="http://www.realprofitsolutions.com">
<s:element name="Test">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="TestResponse">
<s:complexType/>
</s:element>
<s:element name="ProductQueryBatch">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="ProductQueryBatchResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="ProductQueryBatchResult" type="tns:ProductQueryBatchOutput"/>
</s:sequence>
</s:complexType>
</s:element>
<s:complexType name="ProductQueryBatchOutput">
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="Status" type="s:string"/>
<s:element minOccurs="0" maxOccurs="1" name="Error" type="s:string"/>
<s:element minOccurs="1" maxOccurs="1" name="MultiPacked" type="s:boolean"/>
<s:element minOccurs="0" maxOccurs="1" name="Items" type="tns:ArrayOfProductItem"/>
</s:sequence>
</s:complexType>
<s:complexType name="ArrayOfProductItem">
<s:sequence>
<s:element minOccurs="0" maxOccurs="unbounded" name="ProductItem" nillable="true" type="tns:ProductItem"/>
</s:sequence>
</s:complexType>
<s:complexType name="ProductItem">
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="UPC" type="s:string"/>
<s:element minOccurs="1" maxOccurs="1" name="Quantity_On_Hand_V" nillable="true" type="s:int"/>
<s:element minOccurs="1" maxOccurs="1" name="Quantity_On_Hand_T" nillable="true" type="s:int"/>
</s:sequence>
</s:complexType>
<s:element name="OrderInsert">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderInsertResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderInsertResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderDetailInsert">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderDetailInsertResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderDetailInsertResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderCommit">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderCommitResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderCommitResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="ConnectionPoll">
<s:complexType/>
</s:element>
<s:element name="ConnectionPollResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="ConnectionPollResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderVoid">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderVoidResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderVoidResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="ProductQuery">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="ProductQueryResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="ProductQueryResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderQuery">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderQueryResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderQueryResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderQueryShipped">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderQueryShippedResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderQueryShippedResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSubmit">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSubmitResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderSubmitResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSuspendSubmit">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSuspendSubmitResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderSuspendSubmitResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSuspend">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="xmlString" type="s:string"/>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="OrderSuspendResponse">
<s:complexType>
<s:sequence>
<s:element minOccurs="0" maxOccurs="1" name="OrderSuspendResult">
<s:complexType mixed="true">
<s:sequence>
<s:any/>
</s:sequence>
</s:complexType>
</s:element>
</s:sequence>
</s:complexType>
</s:element>
<s:element name="ProductQueryBatchOutput" nillable="true" type="tns:ProductQueryBatchOutput"/>
</s:schema>
</wsdl:types>
<wsdl:message name="TestSoapIn">
<wsdl:part name="parameters" element="tns:Test"/>
</wsdl:message>
<wsdl:message name="TestSoapOut">
<wsdl:part name="parameters" element="tns:TestResponse"/>
</wsdl:message>
<wsdl:message name="ProductQueryBatchSoapIn">
<wsdl:part name="parameters" element="tns:ProductQueryBatch"/>
</wsdl:message>
<wsdl:message name="ProductQueryBatchSoapOut">
<wsdl:part name="parameters" element="tns:ProductQueryBatchResponse"/>
</wsdl:message>
<wsdl:message name="OrderInsertSoapIn">
<wsdl:part name="parameters" element="tns:OrderInsert"/>
</wsdl:message>
<wsdl:message name="OrderInsertSoapOut">
<wsdl:part name="parameters" element="tns:OrderInsertResponse"/>
</wsdl:message>
<wsdl:message name="OrderDetailInsertSoapIn">
<wsdl:part name="parameters" element="tns:OrderDetailInsert"/>
</wsdl:message>
<wsdl:message name="OrderDetailInsertSoapOut">
<wsdl:part name="parameters" element="tns:OrderDetailInsertResponse"/>
</wsdl:message>
<wsdl:message name="OrderCommitSoapIn">
<wsdl:part name="parameters" element="tns:OrderCommit"/>
</wsdl:message>
<wsdl:message name="OrderCommitSoapOut">
<wsdl:part name="parameters" element="tns:OrderCommitResponse"/>
</wsdl:message>
<wsdl:message name="ConnectionPollSoapIn">
<wsdl:part name="parameters" element="tns:ConnectionPoll"/>
</wsdl:message>
<wsdl:message name="ConnectionPollSoapOut">
<wsdl:part name="parameters" element="tns:ConnectionPollResponse"/>
</wsdl:message>
<wsdl:message name="OrderVoidSoapIn">
<wsdl:part name="parameters" element="tns:OrderVoid"/>
</wsdl:message>
<wsdl:message name="OrderVoidSoapOut">
<wsdl:part name="parameters" element="tns:OrderVoidResponse"/>
</wsdl:message>
<wsdl:message name="ProductQuerySoapIn">
<wsdl:part name="parameters" element="tns:ProductQuery"/>
</wsdl:message>
<wsdl:message name="ProductQuerySoapOut">
<wsdl:part name="parameters" element="tns:ProductQueryResponse"/>
</wsdl:message>
<wsdl:message name="OrderQuerySoapIn">
<wsdl:part name="parameters" element="tns:OrderQuery"/>
</wsdl:message>
<wsdl:message name="OrderQuerySoapOut">
<wsdl:part name="parameters" element="tns:OrderQueryResponse"/>
</wsdl:message>
<wsdl:message name="OrderQueryShippedSoapIn">
<wsdl:part name="parameters" element="tns:OrderQueryShipped"/>
</wsdl:message>
<wsdl:message name="OrderQueryShippedSoapOut">
<wsdl:part name="parameters" element="tns:OrderQueryShippedResponse"/>
</wsdl:message>
<wsdl:message name="OrderSubmitSoapIn">
<wsdl:part name="parameters" element="tns:OrderSubmit"/>
</wsdl:message>
<wsdl:message name="OrderSubmitSoapOut">
<wsdl:part name="parameters" element="tns:OrderSubmitResponse"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSubmitSoapIn">
<wsdl:part name="parameters" element="tns:OrderSuspendSubmit"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSubmitSoapOut">
<wsdl:part name="parameters" element="tns:OrderSuspendSubmitResponse"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSoapIn">
<wsdl:part name="parameters" element="tns:OrderSuspend"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSoapOut">
<wsdl:part name="parameters" element="tns:OrderSuspendResponse"/>
</wsdl:message>
<wsdl:message name="TestHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="TestHttpPostOut"/>
<wsdl:message name="ProductQueryBatchHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="ProductQueryBatchHttpPostOut">
<wsdl:part name="Body" element="tns:ProductQueryBatchOutput"/>
</wsdl:message>
<wsdl:message name="OrderInsertHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderInsertHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderDetailInsertHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderDetailInsertHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderCommitHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderCommitHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="ConnectionPollHttpPostIn"/>
<wsdl:message name="ConnectionPollHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderVoidHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderVoidHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="ProductQueryHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="ProductQueryHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderQueryHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderQueryHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderQueryShippedHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderQueryShippedHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderSubmitHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderSubmitHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSubmitHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderSuspendSubmitHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:message name="OrderSuspendHttpPostIn">
<wsdl:part name="xmlString" type="s:string"/>
</wsdl:message>
<wsdl:message name="OrderSuspendHttpPostOut">
<wsdl:part name="Body"/>
</wsdl:message>
<wsdl:portType name="OrderProcessingSoap">
<wsdl:operation name="Test">
<wsdl:input message="tns:TestSoapIn"/>
<wsdl:output message="tns:TestSoapOut"/>
</wsdl:operation>
<wsdl:operation name="ProductQueryBatch">
<wsdl:input message="tns:ProductQueryBatchSoapIn"/>
<wsdl:output message="tns:ProductQueryBatchSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderInsert">
<wsdl:input message="tns:OrderInsertSoapIn"/>
<wsdl:output message="tns:OrderInsertSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderDetailInsert">
<wsdl:input message="tns:OrderDetailInsertSoapIn"/>
<wsdl:output message="tns:OrderDetailInsertSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderCommit">
<wsdl:input message="tns:OrderCommitSoapIn"/>
<wsdl:output message="tns:OrderCommitSoapOut"/>
</wsdl:operation>
<wsdl:operation name="ConnectionPoll">
<wsdl:input message="tns:ConnectionPollSoapIn"/>
<wsdl:output message="tns:ConnectionPollSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderVoid">
<wsdl:input message="tns:OrderVoidSoapIn"/>
<wsdl:output message="tns:OrderVoidSoapOut"/>
</wsdl:operation>
<wsdl:operation name="ProductQuery">
<wsdl:input message="tns:ProductQuerySoapIn"/>
<wsdl:output message="tns:ProductQuerySoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderQuery">
<wsdl:input message="tns:OrderQuerySoapIn"/>
<wsdl:output message="tns:OrderQuerySoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderQueryShipped">
<wsdl:input message="tns:OrderQueryShippedSoapIn"/>
<wsdl:output message="tns:OrderQueryShippedSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSubmit">
<wsdl:input message="tns:OrderSubmitSoapIn"/>
<wsdl:output message="tns:OrderSubmitSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSuspendSubmit">
<wsdl:input message="tns:OrderSuspendSubmitSoapIn"/>
<wsdl:output message="tns:OrderSuspendSubmitSoapOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSuspend">
<wsdl:input message="tns:OrderSuspendSoapIn"/>
<wsdl:output message="tns:OrderSuspendSoapOut"/>
</wsdl:operation>
</wsdl:portType>
<wsdl:portType name="OrderProcessingHttpPost">
<wsdl:operation name="Test">
<wsdl:input message="tns:TestHttpPostIn"/>
<wsdl:output message="tns:TestHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="ProductQueryBatch">
<wsdl:input message="tns:ProductQueryBatchHttpPostIn"/>
<wsdl:output message="tns:ProductQueryBatchHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderInsert">
<wsdl:input message="tns:OrderInsertHttpPostIn"/>
<wsdl:output message="tns:OrderInsertHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderDetailInsert">
<wsdl:input message="tns:OrderDetailInsertHttpPostIn"/>
<wsdl:output message="tns:OrderDetailInsertHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderCommit">
<wsdl:input message="tns:OrderCommitHttpPostIn"/>
<wsdl:output message="tns:OrderCommitHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="ConnectionPoll">
<wsdl:input message="tns:ConnectionPollHttpPostIn"/>
<wsdl:output message="tns:ConnectionPollHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderVoid">
<wsdl:input message="tns:OrderVoidHttpPostIn"/>
<wsdl:output message="tns:OrderVoidHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="ProductQuery">
<wsdl:input message="tns:ProductQueryHttpPostIn"/>
<wsdl:output message="tns:ProductQueryHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderQuery">
<wsdl:input message="tns:OrderQueryHttpPostIn"/>
<wsdl:output message="tns:OrderQueryHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderQueryShipped">
<wsdl:input message="tns:OrderQueryShippedHttpPostIn"/>
<wsdl:output message="tns:OrderQueryShippedHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSubmit">
<wsdl:input message="tns:OrderSubmitHttpPostIn"/>
<wsdl:output message="tns:OrderSubmitHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSuspendSubmit">
<wsdl:input message="tns:OrderSuspendSubmitHttpPostIn"/>
<wsdl:output message="tns:OrderSuspendSubmitHttpPostOut"/>
</wsdl:operation>
<wsdl:operation name="OrderSuspend">
<wsdl:input message="tns:OrderSuspendHttpPostIn"/>
<wsdl:output message="tns:OrderSuspendHttpPostOut"/>
</wsdl:operation>
</wsdl:portType>
<wsdl:binding name="OrderProcessingSoap" type="tns:OrderProcessingSoap">
<soap:binding transport="http://schemas.xmlsoap.org/soap/http"/>
<wsdl:operation name="Test">
<soap:operation soapAction="http://www.realprofitsolutions.com/Test" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ProductQueryBatch">
<soap:operation soapAction="http://www.realprofitsolutions.com/ProductQueryBatch" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderInsert">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderInsert" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderDetailInsert">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderDetailInsert" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderCommit">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderCommit" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ConnectionPoll">
<soap:operation soapAction="http://www.realprofitsolutions.com/ConnectionPoll" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderVoid">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderVoid" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ProductQuery">
<soap:operation soapAction="http://www.realprofitsolutions.com/ProductQuery" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQuery">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderQuery" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQueryShipped">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderQueryShipped" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSubmit">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderSubmit" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspendSubmit">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderSuspendSubmit" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspend">
<soap:operation soapAction="http://www.realprofitsolutions.com/OrderSuspend" style="document"/>
<wsdl:input>
<soap:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap:body use="literal"/>
</wsdl:output>
</wsdl:operation>
</wsdl:binding>
<wsdl:binding name="OrderProcessingSoap12" type="tns:OrderProcessingSoap">
<soap12:binding transport="http://schemas.xmlsoap.org/soap/http"/>
<wsdl:operation name="Test">
<soap12:operation soapAction="http://www.realprofitsolutions.com/Test" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ProductQueryBatch">
<soap12:operation soapAction="http://www.realprofitsolutions.com/ProductQueryBatch" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderInsert">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderInsert" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderDetailInsert">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderDetailInsert" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderCommit">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderCommit" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ConnectionPoll">
<soap12:operation soapAction="http://www.realprofitsolutions.com/ConnectionPoll" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderVoid">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderVoid" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ProductQuery">
<soap12:operation soapAction="http://www.realprofitsolutions.com/ProductQuery" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQuery">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderQuery" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQueryShipped">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderQueryShipped" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSubmit">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderSubmit" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspendSubmit">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderSuspendSubmit" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspend">
<soap12:operation soapAction="http://www.realprofitsolutions.com/OrderSuspend" style="document"/>
<wsdl:input>
<soap12:body use="literal"/>
</wsdl:input>
<wsdl:output>
<soap12:body use="literal"/>
</wsdl:output>
</wsdl:operation>
</wsdl:binding>
<wsdl:binding name="OrderProcessingHttpPost" type="tns:OrderProcessingHttpPost">
<http:binding verb="POST"/>
<wsdl:operation name="Test">
<http:operation location="/Test"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output/>
</wsdl:operation>
<wsdl:operation name="ProductQueryBatch">
<http:operation location="/ProductQueryBatch"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:mimeXml part="Body"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderInsert">
<http:operation location="/OrderInsert"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderDetailInsert">
<http:operation location="/OrderDetailInsert"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderCommit">
<http:operation location="/OrderCommit"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ConnectionPoll">
<http:operation location="/ConnectionPoll"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderVoid">
<http:operation location="/OrderVoid"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="ProductQuery">
<http:operation location="/ProductQuery"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQuery">
<http:operation location="/OrderQuery"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderQueryShipped">
<http:operation location="/OrderQueryShipped"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSubmit">
<http:operation location="/OrderSubmit"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspendSubmit">
<http:operation location="/OrderSuspendSubmit"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
<wsdl:operation name="OrderSuspend">
<http:operation location="/OrderSuspend"/>
<wsdl:input>
<mime:content type="application/x-www-form-urlencoded"/>
</wsdl:input>
<wsdl:output>
<mime:content part="Body" type="text/xml"/>
</wsdl:output>
</wsdl:operation>
</wsdl:binding>
<wsdl:service name="OrderProcessing">
<wsdl:port name="OrderProcessingSoap" binding="tns:OrderProcessingSoap">
<soap:address location="https://www.integratedfulfillment.net/WebSrv/OrderProcessing.asmx"/>
</wsdl:port>
<wsdl:port name="OrderProcessingSoap12" binding="tns:OrderProcessingSoap12">
<soap12:address location="https://www.integratedfulfillment.net/WebSrv/OrderProcessing.asmx"/>
</wsdl:port>
<wsdl:port name="OrderProcessingHttpPost" binding="tns:OrderProcessingHttpPost">
<http:address location="https://www.integratedfulfillment.net/WebSrv/OrderProcessing.asmx"/>
</wsdl:port>
</wsdl:service>
</wsdl:definitions>