class myWidgetFormSchemaFormatterDList extends sfWidgetFormSchemaFormatter
{

  protected
    $rowFormat       = "<dt>%label%</dt>
<dd> custom %error%%field%%help%%hidden_fields%</dd>
",
    $helpFormat      = '<br />%help%',
    $errorRowFormat  = "<dt>Errors:</dt>
<dd>%errors%</dd>
",
    $errorListFormatInARow     = "  <ul class=\"error_list\">
%errors%  </ul>
",
    $errorRowFormatInARow      = "    <li>%error%</li>
",
    $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>
",
    $decoratorFormat = "<dl>
  %content%</dl>";
}


