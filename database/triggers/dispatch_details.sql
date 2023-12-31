CREATE TRIGGER `disminuir_tonelada_usada_en_liquidacion` BEFORE DELETE ON `dispatch_details`
 FOR EACH ROW UPDATE settlements SET wmt_shipped = wmt_shipped - old.wmt WHERE id = old.settlement_id;
CREATE TRIGGER `aumentar_tonelada_usada_en_liquidacion` BEFORE INSERT ON `dispatch_details`
 FOR EACH ROW UPDATE settlements SET wmt_shipped = wmt_shipped + new.wmt WHERE id = new.settlement_id;
CREATE TRIGGER `actualizar_tonelada_usada_en_liquidacion` BEFORE UPDATE ON `dispatch_details`
 FOR EACH ROW UPDATE settlements SET wmt_shipped = wmt_shipped + new.wmt - old.wmt WHERE id = new.settlement_id;
