<?php
class ClienteModel extends Model { 

      // Obtener todos los clientes
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   n.nombre, n.apellido, 
                   j.razon_social, j.nombre_representante
            FROM TB_CLIENTE c
            LEFT JOIN TB_CLIENTE_NATURAL n ON c.id_cliente = n.id_cliente
            LEFT JOIN TB_CLIENTE_JURIDICO j ON c.id_cliente = j.id_cliente 
            ORDER BY c.id_cliente DESC
        ");//WHERE c.activo = 1
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function guardarCliente($data) {
    try {
        $this->db->beginTransaction();

        // Validar si llega ubigeo y es un código válido
        if (!empty($data['id_ubigeo']) && is_numeric($data['id_ubigeo'])) {
            $stmtUbigeo = $this->db->prepare("SELECT id_ubigueo FROM CAT_UBIGEO WHERE codigo_ubigeo = ?");
            $stmtUbigeo->execute([$data['id_ubigeo']]);
            $rowUbigeo = $stmtUbigeo->fetch(PDO::FETCH_ASSOC);

            if (!$rowUbigeo) {
                throw new Exception("Ubigeo no válido: " . $data['id_ubigeo']);
            }

            $id_ubigeo_real = $rowUbigeo['id_ubigueo'];
        } else {
            $id_ubigeo_real = null; // ← insertar como NULL
        }


        if (empty($data['id_cliente'])) {
            // INSERTAR CLIENTE
            $stmt = $this->db->prepare("
                INSERT INTO TB_CLIENTE 
                (id_tipo_documento, tipo_cliente, numero_documento, email, telefono, direccion, id_ubigeo)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['id_tipo_documento'],
                $data['tipo_cliente'],
                $data['numero_documento'],
                $data['email'],
                $data['telefono'],
                $data['direccion'],
                $id_ubigeo_real
            ]);

            $id_cliente = $this->db->lastInsertId();

            if ($data['tipo_cliente'] === 'NATURAL') {
                $stmtN = $this->db->prepare("INSERT INTO TB_CLIENTE_NATURAL (id_cliente, nombre, apellido) VALUES (?, ?, ?)");
                $stmtN->execute([$id_cliente, $data['nombre'], $data['apellido']]);
            } else {
                $stmtJ = $this->db->prepare("INSERT INTO TB_CLIENTE_JURIDICO (id_cliente, razon_social, nombre_representante) VALUES (?, ?, ?)");
                $stmtJ->execute([$id_cliente, $data['razon_social'], $data['nombre_representante']]);
            }

        } else {
            // ACTUALIZAR CLIENTE
            $id_cliente = $data['id_cliente'];

            $stmt = $this->db->prepare("
                UPDATE TB_CLIENTE SET 
                    id_tipo_documento = ?, tipo_cliente = ?, numero_documento = ?, 
                    email = ?, telefono = ?, direccion = ?, id_ubigeo = ?
                WHERE id_cliente = ?
            ");
            $stmt->execute([
                $data['id_tipo_documento'],
                $data['tipo_cliente'],
                $data['numero_documento'],
                $data['email'],
                $data['telefono'],
                $data['direccion'],
                $id_ubigeo_real,
                $id_cliente
            ]);

            if ($data['tipo_cliente'] === 'NATURAL') {
                $stmtN = $this->db->prepare("REPLACE INTO TB_CLIENTE_NATURAL (id_cliente, nombre, apellido) VALUES (?, ?, ?)");
                $stmtN->execute([$id_cliente, $data['nombre'], $data['apellido']]);
            } else {
                $stmtJ = $this->db->prepare("REPLACE INTO TB_CLIENTE_JURIDICO (id_cliente, razon_social, nombre_representante) VALUES (?, ?, ?)");
                $stmtJ->execute([$id_cliente, $data['razon_social'], $data['nombre_representante']]);
            }
        }

        $this->db->commit(); 

        return ['success' => true, 'cliente' => $this->getById($id_cliente)];

    } catch (Exception $e) {
        $this->db->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

    // Eliminar lógico
  public function delete($id) {
      $this->db->beginTransaction();
      try {
          $this->db->prepare("DELETE FROM TB_CLIENTE_NATURAL WHERE id_cliente = ?")->execute([$id]);
          $this->db->prepare("DELETE FROM TB_CLIENTE_JURIDICO WHERE id_cliente = ?")->execute([$id]);
          $this->db->prepare("DELETE FROM TB_CLIENTE WHERE id_cliente = ?")->execute([$id]);

          $this->db->commit();
          return true;
      } catch (PDOException $e) {
          $this->db->rollBack();
          return false;
      }
  }


public function getById($id) {
    $stmt = $this->db->prepare("
        SELECT 
            c.*, 
            n.nombre, n.apellido, 
            j.razon_social, j.nombre_representante,
            u.codigo_ubigeo,
            CASE 
                WHEN c.tipo_cliente = 'NATURAL' THEN 2 
                WHEN c.tipo_cliente = 'JURIDICO' THEN 1 
                ELSE 0 
            END AS tipo
        FROM TB_CLIENTE c
        LEFT JOIN TB_CLIENTE_NATURAL n ON c.id_cliente = n.id_cliente
        LEFT JOIN TB_CLIENTE_JURIDICO j ON c.id_cliente = j.id_cliente
        LEFT JOIN CAT_UBIGEO u ON c.id_ubigeo = u.id_ubigueo
        WHERE c.id_cliente = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

   

    public function actualizarEstado($id_cliente, $activo) {
      $stmt = $this->db->prepare("UPDATE TB_CLIENTE SET activo = ?, actualizado_en = NOW() WHERE id_cliente = ?");
      return $stmt->execute([$activo, $id_cliente]);
    }

public function buscarCoincidencias($query) {
    $stmt = $this->db->prepare("
        SELECT 
            c.id_cliente,
            c.tipo_cliente,
            c.numero_documento,
            c.email,
            c.telefono,
            c.direccion,
            c.id_ubigeo,
            u.codigo_ubigeo,

            n.nombre AS nombre_natural,
            n.apellido AS apellido_natural,

            j.razon_social,
            j.nombre_representante

        FROM TB_CLIENTE c
        LEFT JOIN TB_CLIENTE_NATURAL n ON c.id_cliente = n.id_cliente
        LEFT JOIN TB_CLIENTE_JURIDICO j ON c.id_cliente = j.id_cliente
        LEFT JOIN CAT_UBIGEO u ON c.id_ubigeo = u.id_ubigueo
        WHERE c.numero_documento LIKE :q1
           OR n.nombre LIKE :q2
           OR n.apellido LIKE :q3
           OR j.razon_social LIKE :q4
        LIMIT 10
    ");

    $param = '%' . $query . '%';
    $stmt->bindValue(':q1', $param);
    $stmt->bindValue(':q2', $param);
    $stmt->bindValue(':q3', $param);
    $stmt->bindValue(':q4', $param);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}